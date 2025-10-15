import { createRouter, createWebHistory } from "vue-router";
import Login from "../pages/Login.vue";

async function getAuthToken() {
  const token = await cookieStore.get("auth_token");
  return !!token?.value;
}

const routes = [
  {
    path: "/",
    component: () => import("../pages/Home.vue"),
    meta: { requiresAuth: true },
  },
  { path: "/login", component: Login },
  { path: "/sign-up", component: () => import("../pages/SignUp.vue") },
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, _, next) => {
  const loggedIn = await getAuthToken();

  if (to.meta.requiresAuth && !loggedIn) {
    next({ path: "/login" });
  } else if ((to.path === "/login" || to.path === "/sign-up") && loggedIn) {
    next({ path: "/" });
  } else {
    next();
  }
});
