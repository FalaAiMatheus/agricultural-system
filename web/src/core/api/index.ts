import { env } from "../../env";

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

  const response = await fetch(`${apiUrl}${endpoint}`, {
    method: method ?? "GET",
    ...(method === "POST" || method === "PATCH"
      ? {
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(body),
        }
      : {}),
  });

  if (!response.ok) {
    throw new Error("Credencias inválidas");
  }

  const json = (await response.json()) as TResponse;

  return json;
}
