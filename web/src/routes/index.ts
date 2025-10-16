import {
  createRouter,
  createWebHistory,
  type RouteRecordRaw,
} from "vue-router";
import Login from "../pages/Login.vue";
import { getAuthToken } from "../services/get-auth-token";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    component: () => import("../pages/Home.vue"),
    meta: { requiresAuth: true },
  },
  { path: "/login", component: Login, meta: { guestOnly: true } },
  {
    path: "/sign-up",
    component: () => import("../pages/SignUp.vue"),
    meta: { guestOnly: true },
  },
  {
    path: "/rural-producers",
    component: () => import("../pages/RuralProducers.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/properties",
    component: () => import("../pages/PropertiesPage.vue"),
    meta: { requiresAuth: true },
  },
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, _, next) => {
  const loggedIn = await getAuthToken();

  if (to.meta.requiresAuth && !loggedIn) {
    next({ path: "/login" });
  } else if (to.meta.guestOnly && loggedIn) {
    next({ path: "/" });
  } else {
    next();
  }
});
