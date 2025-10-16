import { api } from "../core/api";
import type { ApiResponse } from "../core/types/api";

export type Herd = {
  id: number;
  species: string;
  quantity: number;
  purpose: string;
  update_date: string;
  property_id: number;
};

export const getAllHerds = async () => {
  const { data } = await api<{ data: Herd[] }>({
    endpoint: "/herds",
    method: "GET",
  });

  return { data };
};

export const createHerd = async (data: Omit<Herd, "id">) => {
  const { message } = await api<ApiResponse>({
    endpoint: "/herds",
    method: "POST",
    body: data,
  });

  return { message };
};

export const updateHerd = async (data: Omit<Herd, "id">, id: string) => {
  const { message } = await api<ApiResponse>({
    endpoint: `/herds/${id}`,
    method: "PATCH",
    body: data,
  });

  return { message };
};

export const deleteHerd = async (id: string) => {
  const { message } = await api<ApiResponse>({
    endpoint: `/herds/${id}`,
    method: "DELETE",
  });

  return { message };
};
