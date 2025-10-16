export async function getAuthToken() {
  const token = await cookieStore.get("auth_token");
  return token?.value;
}
