import Vue from "vue";
import Router from 'vue-router'
import dashboard from './components/dashboard/overview/Home.vue'
import task from './components/dashboard/task/Home.vue'
import user from './components/dashboard/user/Home.vue'
import document from './components/dashboard/document/Home.vue'
import faq from './components/dashboard/faq/Home.vue'
import admin from './components/dashboard/admin/Home.vue'


import userlist from './components/dashboard/user/List.vue'
import userpassword from './components/dashboard/user/Password.vue'
import userassignmachine from './components/dashboard/user/AssignMachine.vue'
import profile from './components/dashboard/profile/Profile.vue'
import permission from './components/dashboard/profile/Permission.vue'
import pages from './components/dashboard/profile/Pages.vue'
import documentadd from './components/dashboard/document/Add.vue'

import faqlist from './components/dashboard/faq/List.vue'
import adminlist from './components/dashboard/admin/List.vue'

import tasklist from './components/dashboard/task/List.vue'
import taskadd from './components/dashboard/task/Add.vue'
import taskedit from './components/dashboard/task/Edit.vue'
import taskdetail from './components/dashboard/task/Detail.vue'
import dashboardoverview from './components/dashboard/overview/OverView.vue'




Vue.use(Router)
export default new Router({
    mode: "history",
    routes: [ 
        {
            path: '/admindashboard/',
            component: dashboard,
            meta: {
                title: 'Dashboard',
            },
            children: [
                {
                    path: '/',
                    component: dashboardoverview,    // for super admin
                    meta: {
                        title: 'Overview',
                    }
                },
              
            ]
        },
        {
            path: '/admindashboard/user',
            component: user,
            meta: {
                title: 'User',
            },
            children: [
                {
                    path: 'list',
                    component: userlist,             //for super admin, bdm, bde
                    meta: {
                        title: 'List',
                    }
                },
                {
                    path: 'password',
                    component: userpassword,       //for super admin, bdm ,bde
                    meta: {
                        title: 'Password',
                    }
                },
                {
                    path: 'assignmachine',
                    component: userassignmachine,       //for super admin, bdm ,bde
                    meta: {
                        title: 'Assign',
                    }
                },
                {
                    path: 'permission',
                    name: 'Permission',
                    component: permission,
                    meta: {
                         title: 'Permission',
                        }
                },
       
            ]
        },
        {
            path: '/admindashboard/profile',
            name: 'Profile',
            component: profile,
            meta: {
                 title: 'Profile',
                }
        },
       
        {
            path: '/dashboard/pageslist/:id',
            name: 'Pages',
            component: pages,
            meta: {
                 title: 'pages',
                }
        },
        {
            path: '/admindashboard/task',
            component: task,
            meta: {
                title: 'task',
            },
            children: [
                {
                    path: 'list',
                    component: tasklist,
                    meta: {
                        title: 'task list',
                    }
                },
                {
                    path: 'add',
                    component: taskadd,
                    meta: {
                        title: 'task Add',
                    }
                },
                {
                    path: 'edit/:id',
                    component: taskedit,
                    meta: {
                        title: 'task Edit',
                    }
                },
                {
                    path: 'detail/:id',
                    component: taskdetail,
                    meta: {
                        title: 'task detail',
                    }
                },
            ]
        },
        

        {
            path: '/admindashboard/faq',
            component: faq,
            meta: {
                title: 'Faq',
            },
            children: [
                {
                    path: 'list',
                    component: faqlist,
                    meta: {
                        title: 'Faq',
                    }
                },
              
              
            ]
        },

        {
            path: '/admindashboard/admin',
            component: admin,
            meta: {
                title: 'Admin',
            },
            children: [
                {
                    path: 'list',
                    component: adminlist,
                    meta: {
                        title: 'Admin',
                    }
                },
              
              
            ]
        },
    

    
       
    
       


      
 
        

        
        

       ]
})