import { api } from "../core/api";
import type { ApiResponse } from "../core/types/api";
import type { Properties } from "./properties";

export type ProductionUnit = {
  id: number;
  culture_name: string;
  property_id: number;
  total_area_ha: string;
  latitude: string;
  longitude: string;
  properties?: Properties;
};

export const getAllProductionUnits = async () => {
  const { data } = await api<{ data: ProductionUnit[] }>({
    endpoint: "/production-units",
    method: "GET",
  });

  return { data };
};

export const createProductionUnit = async (
  data: Omit<ProductionUnit, "id">
) => {
  const { message } = await api<ApiResponse>({
    endpoint: "/production-units",
    method: "POST",
    body: data,
  });

  return { message };
};

export const updateProductionUnit = async (
  data: Omit<ProductionUnit, "id">,
  id: string
) => {
  const { message } = await api<ApiResponse>({
    endpoint: `/production-units/${id}`,
    method: "PATCH",
    body: data,
  });

  return { message };
};

export const deleteProductionUnit = async (id: string) => {
  const { message } = await api<ApiResponse>({
    endpoint: `/production-units/${id}`,
    method: "DELETE",
  });

  return { message };
};
