import { api } from "../core/api";
import type { ApiResponse } from "../core/types/api";

export type RuralProducer = {
  id: number;
  name: string;
  document: string;
  telephone: string;
  email: string;
  address: string;
  registration_date: string;
};

export const getAllRuralProducers = async () => {
  const { data } = await api<{ data: RuralProducer[] }>({
    endpoint: "/producers",
    method: "GET",
  });

  return { data };
};

export const createRuralProducer = async (data: Omit<RuralProducer, "id">) => {
  const { message } = await api<ApiResponse>({
    endpoint: "/producers",
    method: "POST",
    body: data,
  });

  return { message };
};

export const updateRuralProducer = async (
  data: Omit<RuralProducer, "id">,
  id: string
) => {
  const { message } = await api<ApiResponse>({
    endpoint: `/producers/${id}`,
    method: "PATCH",
    body: data,
  });

  return { message };
};

export const deleteRuralProducer = async (id: string) => {
  const { message } = await api<ApiResponse>({
    endpoint: `/producers/${id}`,
    method: "DELETE",
  });

  return { message };
};
