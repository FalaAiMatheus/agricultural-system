import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { getAuthToken } from "../services/get-auth-token";
import { logout } from "../services/logout";

export const useAuthStore = defineStore("auth", () => {
  const token = ref<string | undefined>(undefined);

  const isAuthenticated = computed(() => !!token.value);

  async function loadToken() {
    const res = await getAuthToken();
    token.value = res;
  }

  async function clearToken() {
    await logout();
    cookieStore.delete("auth_token");
    token.value = undefined;
  }

  return { token, isAuthenticated, loadToken, clearToken };
});
