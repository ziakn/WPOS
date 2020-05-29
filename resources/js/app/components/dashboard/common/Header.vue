<template>
  <v-app-bar  color="accent" :clipped-left="$vuetify.breakpoint.lgAndUp" app dark flat >
    <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
      <v-app-bar-nav-icon @click="drawerTrigger"></v-app-bar-nav-icon>
      <span class="hidden-sm-and-down">WPOS</span>
    </v-toolbar-title>
    <v-progress-linear
      :active="showFullLoading"
      :indeterminate="showFullLoading"
      absolute
      bottom
      color="accent"
    ></v-progress-linear>
    <!-- <v-text-field
      flat
      solo-inverted
      hide-details
      prepend-inner-icon="search"
      label="Search"
      class="hidden-sm-and-down"
    ></v-text-field> -->
    <div class="flex-grow-1"></div>
    <v-btn icon large>
      <v-avatar size="32px" item>
        <v-img :src="dataUser.image" :alt="dataUser.name"></v-img>
      </v-avatar>
    </v-btn>
      <v-menu bottom left>
            <template v-slot:activator="{ on }">
              <v-btn
                dark
                icon
                v-on="on"
              >
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </template>

            <v-list>
              <v-list-item
                v-for="(item, i) in items"
                :key="i"
                @click=""
              >
                <v-list-item-title>{{ item.title }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
  </v-app-bar>
</template>

<script>
export default {
  props: {
    source: String
  },
  data: () => ({
    drawer: false,
    dialog: false,
     items: [
        { title: 'My Account' },
        { title: 'Team Settings' },
        { title: 'Create Team' },
        { title: 'Default Team' },
        { title: 'LogOut' },
      ],
    dataUser: {}
  }),
  created() {
    this.dataUser = this.$store.state.authUser;
  },
  methods: {
    drawerTrigger() {
      this.drawer = !this.drawer;
      this.$emit("send", this.drawer);
    }
  }
};
</script>