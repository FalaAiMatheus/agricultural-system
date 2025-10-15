<template>
  <section class="w-full flex justify-center items-center h-screen">
    <Card class="w-96">
      <template #title>
        <span class="leading-none font-semibold text-xl text-center"
          >Sistema Agropecuário 🚜 - Entrar</span
        >
      </template>
      <template #content>
        <Toast />
        <Form
          v-slot="$form"
          :initialValues
          :resolver
          @submit="onFormSubmit"
          class="flex flex-col gap-4 w-full"
        >
          <div class="flex flex-col gap-1">
            <InputText name="name" type="text" placeholder="Nome" fluid />
            <Message
              v-if="$form.email?.invalid"
              severity="error"
              size="small"
              variant="simple"
              >{{ $form.email.error.message }}</Message
            >
          </div>
          <div class="flex flex-col gap-1">
            <InputText name="email" type="email" placeholder="Email" fluid />
            <Message
              v-if="$form.email?.invalid"
              severity="error"
              size="small"
              variant="simple"
              >{{ $form.email.error.message }}</Message
            >
          </div>
          <div class="flex flex-col gap-1">
            <Password
              name="password"
              placeholder="Senha"
              :feedback="false"
              toggleMask
              fluid
            />
            <Message
              v-if="$form.password?.invalid"
              severity="error"
              size="small"
              variant="simple"
            >
              <ul class="my-0 px-4 flex flex-col gap-1">
                <li
                  v-for="(error, index) of $form.password.errors"
                  :key="index"
                >
                  {{ error.message }}
                </li>
              </ul>
            </Message>
          </div>
          <div class="flex flex-col gap-2 w-full">
            <Button type="submit" label="Entrar" class="w-full" />
            <Button label="Criar conta" variant="text" class="w-full" />
          </div>
        </Form>
      </template>
    </Card>
  </section>
</template>

<script setup lang="ts">
import { Form } from "@primevue/forms";
import { zodResolver } from "@primevue/forms/resolvers/zod";
import {
  Button,
  Card,
  InputText,
  Message,
  Password,
  Toast,
  useToast,
} from "primevue";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { z } from "zod/v4";
import { api } from "../../../core/api";
import type { AuthResponse } from "../../../core/types/api";

const toast = useToast();
const router = useRouter();

const initialValues = ref({
  name: "",
  email: "",
  password: "",
});

const resolver = zodResolver(
  z.object({
    name: z.string().min(1, "Nome é obrigatório"),
    email: z.email({
      error: (issue) =>
        issue.input === undefined ? "Email é obrigatório" : "Email inválido",
    }),
    password: z
      .string()
      .min(1, "Senha é obrigatória")
      .min(8, "A senha precisa de no mínimo 8 caracteres"),
  })
);

const onFormSubmit = async (e: any) => {
  api<AuthResponse>({
    endpoint: "/auth/register",
    method: "POST",
    body: {
      ...e.values,
      role: "GUEST",
    },
  })
    .then((res) => {
      cookieStore.set("auth_token", res.access_token);
      toast.add({
        severity: "success",
        life: 3000,
        summary: res.access_token,
      });
      router.push("/");
    })
    .catch((error) => {
      toast.add({
        severity: "error",
        life: 3000,
        summary: error,
      });
    });
};
</script>
