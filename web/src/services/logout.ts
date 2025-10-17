import { api } from "../core/api";
import type { ApiResponse } from "../core/types/api";

export const logout = async () => {
  const { message } = await api<ApiResponse>({
    endpoint: "/auth/logout",
    method: "POST",
  });

  return { message };
};
