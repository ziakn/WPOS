<template>
    <v-content>
        <v-container fluid>
            <v-row justify="center">
                <v-col sm="12" md="12" lg="11">
                    <v-toolbar flat color="white">
                        <v-toolbar-title>Admin List</v-toolbar-title>
                        <v-divider class="mx-2" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-text-field
                            dense
                            v-model="search"
                            append-icon="search"
                            label="Search"
                            hide-details
                            outlined
                        ></v-text-field>
                    </v-toolbar>
                    <v-data-table :headers="headers" :items="dataList" :search="search" class="elevation-1" >
                        <template v-slot:item.action="{ item }">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-icon color="green" @click="editPassword(item)" v-on="on"  >lock</v-icon>
                                </template>
                                <span>Edit </span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-icon color="blue" @click="editItem(item)" v-on="on"  >edit</v-icon>
                                </template>
                                <span>Edit </span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-icon  color="red"   @click="deleteItem(item)" v-on="on" >delete</v-icon>
                                </template>
                                <span>Delete</span>
                            </v-tooltip>
                        </template>
                        <template v-slot:no-data>
                        <NoDataList :loading="loading" @initialize="initialize"></NoDataList>
                      </template>
                    </v-data-table>
                    <div class="text-center">
                        <v-pagination
                            v-model="filters.page"
                            :length="pageCount"
                            @input="getAdmin"
                        ></v-pagination>
                    </div>
                </v-col>
            </v-row>
        </v-container>
        <v-btn  bottom color="accent" dark fab fixed  right @click="dialog = !dialog">
            <v-icon>mdi-plus</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" max-width="600px" persistent>
            <v-card>
                <v-card color="primary" dark :tile="true" flat>
                    <v-card-title
                        class="headline"
                        v-text="formTitle"
                    ></v-card-title>
                </v-card>
                <v-card-title> </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm12 md12>
                                <v-text-field
                                    v-model="editedItem.name"
                                    label="Name"
                                    :rules="[v => !!v || 'Name is required']"
                                    required
                                    outlined
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                               <v-text-field 
                                :rules="emailRules" 
                                v-model="editedItem.email" 
                                label="Email"
                                outlined
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12s md12 v-if="edit">
                                <v-text-field
                                v-model="editedItem.password"
                                :rules="passwordRules"
                                label="Password"
                                type="password"
                                filled
                                ></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" text @click="close" >Cancel</v-btn>
                    <v-btn  color="primary" :loading="loading" :disabled="loading" text @click="save">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>  
         <v-dialog v-model="dialog1" max-width="600px">
              <v-card>
               <v-card color="primary" dark :tile="true" flat>
                    <v-card-title  class="headline">Edit Password</v-card-title>
                </v-card>
                <v-card-text>
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs12 sm12s md12 >
                        <v-text-field
                          v-model="editedItem.password"
                          :rules="passwordRules"
                          label="Password"
                          type="text"
                          outlined
                        ></v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-container>
              </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="red darken-1" text @click="close">Cancel</v-btn>
                  <v-btn color="primary" text @click="changePassword" :loading="loading" :disabled="loading">Save</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog> 
        <DeleteModal :trigger="isDelete" :title="deleteTitle" :body="deleteBody" @request="remove"></DeleteModal>
        <v-snackbar
            v-model="snackbar"
            :vertical="snackvertical"
            :timeout="snacktimeout"
            :top="snacktop"
            :color="snackcolor"
        >
            {{ snacktext }}
            <v-btn color="white" text @click="snackbar = false">
                Close
            </v-btn>
        </v-snackbar>
    </v-content>
</template>

<script>
import DeleteModal from "./../common/DeleteModal";
import NoDataList from "./../common/NoDataList";
export default {
    components: {
        DeleteModal,
        NoDataList
    },
    data: () => ({
        userType: null,
        loading: false,
        dialog1:false,
        snackbar: false,
        dataIndex: null,
        search: "",
        deleteTitle: "",
        deleteBody: "",
        isDelete: false,
        edit: true,
        dialog: false,
        itemsPerPage: 1,
        pageCount: 2,
        mode: "",
        timeout: 6000,
        text: "Hello, I'm a snackbar",
        dialog: false,
        dataList: [],
        headers:
        [
            { text: "ID", align: "left", value: "id" },
            { text: "Name", value: "name" },
            { text: "Email", value: "email" },
            { text: "Action", value: "action" }
        ],
        emailRules: [
            v => !!v || "E-mail is required",
            v => /.+@.+.\.+.+/.test(v) || "E-mail must be valid" 
            ],
        passwordRules: [
            v => (v || "").length >= 8 || `A minimum of 8 characters is allowed`
            ],
        editedIndex: -1,
        editedItem: 
        {
            name: "",
            email: "",
            password: "",
        },
        defaultItem: 
        {
            name: "",
            email: "",
            password: "",
        },
        filters: 
        {
            show: 20,
            page: 1
        }
    }),
    props: {
        source: String
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? "New Admin" : " Edit Admin";
        }
    },
    watch: {},
   created() 
    {
        this.initialize();
    },

  methods: 
    {
        async initialize() 
            {
              this.filters.page=1
               this.getAdmin();
            },

        async getAdmin()
            {
                 try 
                {
                    let { data } = await axios({
                    method: "get",
                    url: "/appadmin/createadmin/",
                    params: this.filters
                    });
                    this.dataList =  data.data;
                    this.itemsPerPage=per_page;
                    this.pageCount=last_page;
                    this.filters.page=current_page
                }
                catch (e) 
                {

                }
            },

        async changePassword()
            {
                console.log(this.editedItem)
                  this.loading = true;		
                  try 
                    {
                    let { data } = await axios({
                      method: "post",
                      url: "/appadmin/createadminupdatepassword",
                      data: this.editedItem

                    });
                    if (data.status) {
                      this.snacks("Successfully Changed", "green");
                      this.close();
                    } else {
                      this.snacks("Failed", "red");
                      this.loading = false;
                    }
                  } catch (e) {
                    this.snacks("Error, Server issue", "red");
                    this.loading = false;
                  }
            },

       editPassword(item) 
            {
            this.edit = false;
            this.editedIndex = this.dataList.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.editedItem.password = Math.random().toString(36).slice(-10);
            this.dialog1 = true;
            },
        editItem(item) 
            {
                    this.edit = false;
                    this.editedIndex = this.dataList.indexOf(item);
                    this.editedItem = Object.assign({}, item);
                    this.dialog = true;
            },

        deleteItem(item) 
            {
    	            this.dataIndex = this.dataList.indexOf(item);
			        this.deleteTitle = "Are you sure you want to delete this item?";
			        this.isDelete = !this.isDelete;
            },

        close() 
            {
                    this.dialog = false;
                     this.dialog1 = false;
                      this.loading = false;
                    setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1;
                    }, 300);
            },

        async save() 
            {
                // if (this.$refs.form.validate())
                // {
                if (this.editedIndex > -1)
                {
                try
                {
                   this.loading=true 
                   let { data } = await axios({
                   method: "put",
                   url: "/appadmin/createadmin/" + this.dataList[this.editedIndex].id,
                   data: this.editedItem
                  });
                 if(data.status)
                    {
                        this.snacks('Successfully Done','green')
                        Object.assign(this.dataList[this.editedIndex], this.editedItem);
                        this.loading=false
                        this.close();
                     }
                else
                    {
                        this.snacks("Successfully Done", "red");
                        this.loading = false;
                    }
                } 
                catch (e) 
                    {
                        this.snacks("Failed", "red");
                        this.loading = false;
                    }
                } 
               else
                {
                try 
                {
                    this.loading=true
                    let { data } = await axios({
                    method: "post",
                    url: "/appadmin/createadmin/",
                    data: this.editedItem
                 });
                if (data.status) 
                    {
                        this.snacks('Successfully Done','green')
                        this.dataList.unshift(data.data);
                        this.loading=false
                        this.close();
                     }
                 else
                    {
						this.snacks("Not Successfully Done ", "red");
						this.loading = false;
					}
                } 
                catch (e) 
                {
                    this.snacks("Failed", "red");
					this.loading = false;
                }
                }
                // }
            },

    async remove() 
        {
            try 
            {
    				let { data } = await axios({
					method: "delete",
					url: "/appadmin/createadmin/" + this.dataList[this.dataIndex].id
				    });
                    if (data.status) 
                    {
                        this.snacks('Successfully Done','green')
                        this.dataList.splice(this.dataIndex, 1);
                        this.close();				
				    }
                    else
                    {
                        this.snacks(data.data,'red')
                        this.loading = false;
                    }

            }
            catch (e) 
            {
                    this.snacks('Operation Failed','red')
                    this.loading = false;
			}
		}
  }
};
</script>
