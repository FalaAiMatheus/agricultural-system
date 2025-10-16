import { api } from "../core/api";
import type { ApiResponse } from "../core/types/api";

export type Properties = {
  id: string;
  name: string;
  municipality: string;
  uf: string;
  state_registration: number;
  total_area: string;
  producer_id: string;
};

export const getAllProperties = async () => {
  const { data } = await api<{ data: Properties[] }>({
    endpoint: "/properties",
    method: "GET",
  });

  return { data };
};

export const createProperty = async (data: Omit<Properties, "id">) => {
  const { message } = await api<ApiResponse>({
    endpoint: "/properties",
    method: "POST",
    body: data,
  });

  return { message };
};

export const updateProperty = async (
  data: Omit<Properties, "id">,
  id: string
) => {
  const { message } = await api<ApiResponse>({
    endpoint: `/properties/${id}`,
    method: "PATCH",
    body: data,
  });

  return { message };
};

export const deleteProperty = async (id: string) => {
  const { message } = await api<ApiResponse>({
    endpoint: `/properties/${id}`,
    method: "DELETE",
  });

  return { message };
};
