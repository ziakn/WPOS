<template>
  <v-content>
    <v-container fluid>
      <v-row justify="center">
        <v-col sm="12" md="6" lg="6">
          <v-row>
            <v-col lg="12">
              <v-card>
                <v-card-title>
                  <span class="title font-weight-light" dark>User Profile</span>
                </v-card-title>
                <v-card-text>
                  <v-row>
                    <v-col sm="12" md="12" lg="6">
                      <v-text-field v-model="dataList.id" label="ID" required disabled filled></v-text-field>
                    </v-col>

                    <!-- <v-col sm="12" md="12" lg="4">
                      <v-text-field
                        v-model="dataList.user_type_id"
                        label="User type ID"
                        required
                        disabled
                        filled
                      ></v-text-field>
                    </v-col>

                    <v-col sm="12" md="12" lg="4">
                      <v-text-field v-model="dataList.user_type_id" label="Role" required disabled filled></v-text-field>
                    </v-col> -->
                    <v-col sm="12" md="12" lg="6">
                      <v-text-field
                        v-model="dataList.email"
                        label="Email Address"
                        required
                        disabled
                        filled
                      ></v-text-field>
                    </v-col>
                    <v-col sm="12" md="12" lg="12">
                      <v-text-field v-model="dataList.name" label="Name" required filled></v-text-field>
                    </v-col>

                  </v-row>

                  <v-row align="center">
                    <div class="ml-5">
                       <v-tooltip bottom>
                          <template v-slot:activator="{ on }">
                              <v-btn dark color="accent" v-on="on" @click="editProfile()">Update</v-btn>
                          </template>
                          <span>Update Profile </span>
                        </v-tooltip>
                    </div>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-col>

        <v-col sm="12" md="4" lg="4">
          <v-card max-width="344" class="mx-auto">
            <v-img
              :src="dataList.image"
              height="194"
            ></v-img>
             <v-card-title>
                {{ dataList.name }}
              </v-card-title>

            <v-card-text>
            </v-card-text>
            <v-card-actions>
              <v-btn dark color="indigo  darken-3" @click="changePassword()">Password</v-btn> 
              <v-btn :loading="loading" :disabled="loading" dark color="blue-grey" @click="$refs.inputUpload.click()">
                    Image<v-icon right >add_a_photo</v-icon>
                  </v-btn>
                  <input
                    v-show="false"
                    ref="inputUpload"
                    accept="image/*"
                    type="file"
                    @change="profileChange"
                  />
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>

      <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card>
          <v-card-title>
            <span class="headline">User Profile</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col  cols="12">
                  <v-text-field
                    v-model="password.oldPassword"
                    label="Current Password*"
                    :rules="passwordRules"
                    required
                    filled
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="password.newPassword"
                    label="New Password*"
                    :rules="passwordRules"
                    required
                    filled
                  ></v-text-field>
                </v-col>

                <v-col cols="12">
                  <v-text-field
                    v-model="password.confirmPassword"
                    :rules="[
                    v => v == password.newPassword || 'Should be same as new password']"
                    label="Confirm Password*"
                    required
                    filled
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-container>
            <small>*indicates required field</small>
          </v-card-text>
          <v-card-actions>
            <div class="flex-grow-1"></div>
            <v-btn color="red darken-1" text @click="dialog = false">Close</v-btn>
            <v-btn
              color="primary"
              :loading="loading"
              :disabled="loading"
              text
              @click="save "
            >Save</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-snackbar
        v-model="snackbar"
        :vertical="snackvertical"
        :timeout="snacktimeout"
        :top="snacktop"
        :color="snackcolor"
      >
        {{ snacktext }}
        <v-btn color="white" text @click="snackbar = false">Close</v-btn>
      </v-snackbar>
    </v-container>
  </v-content>
</template>

<script>
export default {
  data: () => ({
     imageFile:null,
    absolute: true,
    loading: false,
    snackbar: false,
    y: "top",
    x: null,
    mode: "",
    timeout: 6000,
    text: "Hello, I'm a snackbar",
    edit: false,
    dialog: false,
    dataUser: [],
    dataList: {},
    password: {
      oldPassword: "",
      newPassword: "",
      confirmPassword: ""
    },
    passwordRules: [
      v => (v || "").length >= 8 || `Minimum Password charecter is 8`
    ],
    confirmPasswordRules: [
      v => (v || "").length >= 8 || `Minimum Password charecter is 8`,
      v => v == password.newPassword || "Should be same as new password"
    ]
  }),
  watch: {},
  created() {
    
    this.initialize();
  },
  methods: {
    async editProfile() {
      try {
        let { data } = await axios({
          method: "post",
          url: "/appadmin/updateUser",
          data: this.dataList
        });
        this.snacks("Successfully Done", "green");
        this.close();
      } catch (e) {
        this.loading = false;
        this.snacks("Operation Failed", "red");
      }
    },
    async changePassword() {
      this.dialog = true;
    },
    async save() {
      this.loading = true;
      try {
        let { data } = await axios({
          method: "post",
          url: "/appadmin/changepassword",
          data: this.password
        });

        if (data.status) {
          this.snacks("Successfully Changed", "green");
        } else {
          this.snacks(data.message, "red");
        }
        this.close();
      } catch (e) {
        this.loading = false;
        this.snacks("Operation Failed", "red");
      }
    },
    
    close() {
      this.dialog = false;
      this.loading = false;
    },
    async initialize() {
      this.start();
      try {
        let { data } = await axios({
          method: "get",
          url: "/appadmin/profile/"
        });
        this.dataList = data;
      } catch (e) {
        this.fail();
      }
      this.finish();
    },
    async profileChange(e) {
      this.imageFile = event.target.files[0];
      this.upload();
    },
    async upload() {
      this.loading=true;
      const formData = new FormData();
      formData.append("myFile", this.imageFile, this.imageFile.name);
      try {
        let { data } = await axios({
          method: "post",
          url: "/appadmin/avatar",
          data: formData
        });
        this.dataList.image = data.data;
        this.snacks("Successfully Uploaded", "green");
        this.loading=false;
      } catch (error) {
        this.snacks("Operation Failed", "red");
        this.loading=false;
      }
    },
  },
  
};
</script>

<style>
</style>