<template>
  <div>
    <v-navigation-drawer
      v-model="trigger"
      :clipped="$vuetify.breakpoint.lgAndUp"
      color="accent"
      dark
      app
    >
      <v-list shaped dense >
        <template v-for="(item,index) in items" >
          <v-row v-if="item.heading" :key="item.heading"  align="center">
            <v-col cols="6">
              <v-subheader v-if="item.heading">{{ item.heading }}</v-subheader>
            </v-col>
            <v-col cols="6" class="text-center">
              <a href="#!" class="body-2 black--text">EDIT</a>
            </v-col>
          </v-row>
          <v-list-group
            v-else-if="item.children"
            :key="index"
            v-model="item.model"
            :prepend-icon="item.model ? item.icon : item['icon-alt']"
            append-icon
            v-show="handleGoToMenus(item)"
            active-class="black white--text"
          >
            <template v-slot:activator>
              <v-list-item  >
                <v-list-item-content>
                  <v-list-item-title>{{ item.text }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </template>
            <v-list-item
              v-for="(child, i) in item.children"
              :key="i"
              :to="handleGoToMenu('/admindashboard/'+child.link)"
                 v-show="showChild(child)"
              active-class="deep-purple white--text"
            >
            <v-flex>
              <v-list-item-action v-if="child.icon">
                <v-icon >{{ child.icon }}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title >{{ child.text }}</v-list-item-title>
              </v-list-item-content>
              </v-flex>
            </v-list-item>
          </v-list-group>
          <v-list-item v-else :key="item.text" >
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ item.text }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list>
      <template v-slot:append>
        <div class="pa-2">
          <v-btn block href="/logoutadmin">Logout</v-btn>
        </div>
      </template>
    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  props: {
    trigger: true
  },
  data: () => ({
    drawer: null,
    userType:null,
    dataUser:[],
    i:0,
    items: [
      {
        icon: "keyboard_arrow_down",
        "icon-alt": "dashboard",
        text: "Dashboard",
        model: false,
         parentAllowed:'',
        children: [
          { text: "Overview", link: "" , },
          ]
      },
       {
        icon: "keyboard_arrow_down",
        "icon-alt": "face",
        text: "Users",
        model: false,
        children: [
          { text: "User List", link: "user/list" ,  },
          // { text: "User Password", link: "user/password" , },
          // { text: "Assign Machine", link: "user/assignmachine" , },
          //  { text: "Permission", link: "user/permission" , },
        ]
      },
      {
        icon: "keyboard_arrow_down",
        "icon-alt": "home_work",
        text: "Subscriptions",
        model: false,
        children: [
          // { text: "Machine List", link: "task/list" , },
        ]
      },
       
       {
        icon: "keyboard_arrow_down",
        "icon-alt": "emoji_people",
        text: "Teams",
        model: false,
        children: [
          // { text: "Add Doc", link: "document/add" ,  },  
        ]
      },
       {
        icon: "keyboard_arrow_down",
        "icon-alt": "offline_bolt",
        text: "Plans",
        model: false,
        children: [
          // { text: "Add Doc", link: "document/add" ,  },  
        ]
      },
       {
        icon: "keyboard_arrow_down",
        "icon-alt": "question_answer",
        text: "FAQs",
        model: false,
        children: [
          { text: "List", link: "faq/list"},  
        ]
      },
       {
        icon: "keyboard_arrow_down",
        "icon-alt": "supervisor_account",
        text: "Admin",
        model: false,
        children: [
          { text: "List", link: "admin/list"   },  
        ]
      },
       {
        icon: "keyboard_arrow_down",
        "icon-alt": "attach_money",
        text: "Currencies",
        model: false,
        children: [
          // { text: "Add Doc", link: "document/add" ,  },  
        ]
      },
      
      {
        icon: "keyboard_arrow_down",
        "icon-alt": "settings_applications",
        text: "Setting",
        model: false,
        children: [
          { text: "Profile", link: "profile" , },
         
        ]
      },

    ]
  }),
  created() 
    {
    this.userType = this.$store.state.authUser.id
    this.initialize();

    },
  methods: 
  {

    async initialize() {
      // try 
      // {
      //   let { data } = await axios({
      //     method: "get",
      //     url: "/app/getuserpages/"+this.userType
      //   });
      //   this.dataUser = data;
       
      // } catch (e) {}

    },
    handleGoToMenu(d) 
    {
      return d;
    },
    handleGoToMenus(item) 
    {
      
        // if(item.parentAllowed==1)
        // {
          return true
        // }
        // return false;
    },
    showChild(child)
    {
      
        return child
      
    },

  },
    

};
</script>