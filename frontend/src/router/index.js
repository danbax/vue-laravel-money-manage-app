import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: "/",
      component: () => import("@/views/Home"),
      children: [
        {
          path: "",
          name: "home",
          component: () => import("@/views/Home")
        },
      ]
    },
    {
      name: "login",
      path: "/login",
      component: () => import("@/views/Login")
    },
    {
      name: "add-record",
      path: "/add",
      component: () => import("@/views/AddRecord")
    },
    {
      name: "register",
      path: "/register",
      component: () => import("@/views/Register")
    },
    {
      name: "settings",
      path: "/settings",
      component: () => import("@/views/Settings")
    },
    // Handle child routes with a default, by giving the name to the
    // child.
    // SO: https://github.com/vuejs/vue-router/issues/777
    // {
    //   path: "/@:username",
    //   component: () => import("@/views/Profile"),
    //   children: [
    //     {
    //       path: "",
    //       name: "profile",
    //       component: () => import("@/views/ProfileArticles")
    //     },
    //     {
    //       name: "profile-favorites",
    //       path: "favorites",
    //       component: () => import("@/views/ProfileFavorited")
    //     }
    //   ]
    // },
  ]
});