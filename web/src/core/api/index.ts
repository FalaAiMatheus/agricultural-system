import { env } from "../../env";
import { getAuthToken } from "../../services/get-auth-token";

export type ApiProps = {
  endpoint: string;
  body?: unknown;
  method: "GET" | "POST" | "DELETE" | "PATCH";
};

export async function api<TResponse = unknown>({
  endpoint,
  method,
  body,
}: ApiProps) {
  const apiUrl = env.data?.VITE_API_URL;
  const apiToken = await getAuthToken();

  const response = await fetch(`${apiUrl}${endpoint}`, {
    method: method ?? "GET",
    headers: {
      Authorization: `Bearer ${apiToken}`,
      Accept: "application/json",
      ...(method === "POST" || method === "PATCH"
        ? { "Content-Type": "application/json" }
        : {}),
    },
    ...(method === "POST" || method === "PATCH"
      ? { body: JSON.stringify(body) }
      : {}),
  });

  if (!response.ok) {
    const errorData = await response.json();
    throw new Error(errorData.message || "Erro desconhecido");
  }

  const json = (await response.json()) as TResponse;

  return json;
}
