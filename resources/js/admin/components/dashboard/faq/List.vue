<template>
    <v-content>
        <v-container fluid>
            <v-row justify="center">
                <v-col sm="12" md="12" lg="11">
                    <v-toolbar flat color="white">
                        <v-toolbar-title>FAQS List</v-toolbar-title>
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
                       <template v-slot:item.show_on_pricing="{ item }">
                         <v-switch v-model="item.show_on_pricing" @change="changeStatus(item)" class="mx-2"></v-switch>
                       </template>
                      
                        <template v-slot:item.action="{ item }">
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
                            @input="getFaq"
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
                                    v-model="editedItem.question"
                                    label="Question"
                                    :rules="[v => !!v || 'Question is required']"
                                    required
                                    outlined
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                                <v-textarea
                                    v-model="editedItem.answer"
                                    label="Answer"
                                    outlined
                                ></v-textarea>
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
            { text: "Question", value: "question" },
            { text: "Answer", value: "answer" },
            { text: "Show on price", value: "show_on_pricing" },
            { text: "Action", value: "action" }
        ],
        editedIndex: -1,
        editedItem: 
        {
            question: "",
            answer: "",
            show_on_pricing: 0
        },
        defaultItem: 
        {
            question: "",
            answer: "",
            show_on_pricing: 0
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
            return this.editedIndex === -1 ? "New Faq" : " Edit Faq";
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
               this.getFaq();
            },

        async getFaq()
            {
                 try 
                {
                    let { data } = await axios({
                    method: "get",
                    url: "/appadmin/faq/",
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

        async changeStatus(i)
            {
                  this.loading = true;		
                  try 
                    {
                    let { data } = await axios({
                      method: "get",
                      url: "/appadmin/faq/"+i.id+"/edit",
                      params:{
                        show_on_pricing: i.show_on_pricing
                      },

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
                   url: "/appadmin/faq/" + this.dataList[this.editedIndex].id,
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
                        this.snacks("Coupon Type Allready used in database", "red");
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
                    url: "/appadmin/faq/",
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
						this.snacks("Coupon Type Allready used in database", "red");
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
					url: "/appadmin/faq/" + this.dataList[this.dataIndex].id
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
