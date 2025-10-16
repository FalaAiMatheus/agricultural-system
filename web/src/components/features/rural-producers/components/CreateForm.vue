<template>
  <Button label="Criar produtor" @click="visible = true" />
  <Dialog
    v-model:visible="visible"
    modal
    header="Criar produtor rural"
    :style="{ width: '25rem' }"
  >
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
          v-if="$form.name?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.name?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputText name="email" type="email" placeholder="Email" fluid />
        <Message
          v-if="$form.name?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.email?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-2">
        <RadioButtonGroup name="document_type" class="flex flex-wrap gap-4">
          <div class="flex items-center gap-2">
            <RadioButton inputId="cpf" value="CPF" />
            <label for="cpf">CPF</label>
          </div>
          <div class="flex items-center gap-2">
            <RadioButton inputId="cnpj" value="CNPJ" />
            <label for="cnpj">CNPJ</label>
          </div>
        </RadioButtonGroup>
        <Message
          v-if="$form.document_type?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.document_type.error?.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputMask
          v-if="$form.document_type?.value === 'CPF'"
          name="document"
          mask="999.999.999-99"
          placeholder="999.999.999-99"
          fluid
        />
        <InputMask
          v-else-if="$form.document_type?.value === 'CNPJ'"
          name="document"
          mask="99.999.999/9999-99"
          placeholder="99.999.999/9999-99"
          fluid
        />
        <Message
          v-if="$form.document?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.document?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputMask
          name="telephone"
          mask="(99) 99999-9999"
          placeholder="(99) 99999-9999"
          fluid
        />
        <Message
          v-if="$form.telephone?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.telephone?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputText name="address" type="text" placeholder="Endereço" fluid />
        <Message
          v-if="$form.address?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.address?.error.message }}</Message
        >
      </div>
      <div class="flex justify-end gap-2">
        <Button
          type="button"
          label="Cancelar"
          severity="secondary"
          @click="visible = false"
        ></Button>
        <Button type="submit" label="Criar"></Button>
      </div>
    </Form>
  </Dialog>
</template>

<script setup lang="ts">
import { Form } from "@primevue/forms";
import { zodResolver } from "@primevue/forms/resolvers/zod";
import {
  Button,
  Dialog,
  InputMask,
  InputText,
  Message,
  RadioButton,
  RadioButtonGroup,
  useToast,
} from "primevue";
import { ref } from "vue";
import { z } from "zod";
import { createRuralProducer } from "../../../../services/rural-producers";

const visible = ref(false);
const initialValues = ref({
  name: "",
  email: "",
  address: "",
  document: "",
  document_type: "",
  telephone: "",
});
const toast = useToast();

const resolver = zodResolver(
  z.object({
    name: z.string().min(1, "Nome é obrigatório"),
    email: z.email({
      error: (issue) =>
        issue.input === undefined ? "Email é obrigatório" : "Email inválido",
    }),
    document_type: z.string().min(1, "Selecione um tipo de documento"),
    address: z.string().min(1, "Endereço é obrigatório"),
    document: z.string().min(1, "Documento é obrigatório"),
    telephone: z.string().min(1, "Telefone é obrigatório"),
  })
);

const onFormSubmit = async (e: any) => {
  const { document_type, ...payload } = e.values;

  if (e.valid) {
    createRuralProducer(payload)
      .then((res) => {
        toast.add({
          severity: "success",
          life: 3000,
          summary: res.message,
        });
        visible.value = false;
      })
      .catch((error) => {
        if (error instanceof Error) {
          toast.add({
            severity: "error",
            life: 3000,
            summary: error.message,
          });
        }
        toast.add({
          severity: "error",
          life: 3000,
          summary: "Ocorreu um erro ao criar o produtor",
        });
      });
  }
};
</script>
