<template>
  <Button label="Criar propriedade" @click="visible = true" />
  <Dialog
    v-model:visible="visible"
    modal
    header="Criar propriedade"
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
        <InputMask
          name="state_registration"
          mask="999999999"
          placeholder="999999999"
          fluid
        />
        <Message
          v-if="$form.state_registration?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.state_registration?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <Select
          name="producer.id"
          :options="ruralProducers"
          optionLabel="name"
          optionValue="id"
          placeholder="Selecione o Produtor rural"
          fluid
        />
        <Message
          v-if="$form.producer?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.producer?.error?.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputText name="uf" type="text" placeholder="UF" fluid />
        <Message
          v-if="$form.uf?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.uf?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputText
          name="total_area"
          type="text"
          placeholder="Area total"
          fluid
        />
        <Message
          v-if="$form.total_area?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.total_area?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputText
          name="municipality"
          type="text"
          placeholder="Município"
          fluid
        />
        <Message
          v-if="$form.municipality?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.municipality?.error.message }}</Message
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
  Select,
  useToast,
} from "primevue";
import { onMounted, ref } from "vue";
import { z } from "zod";
import { createProperty } from "../../../../services/properties";
import { getAllRuralProducers } from "../../../../services/rural-producers";

const visible = ref(false);
const initialValues = ref({
  name: "",
  municipality: "",
  uf: "",
  state_registration: "",
  total_area: "",
  producer: { id: "" },
});
const toast = useToast();
const ruralProducers = ref();

const resolver = zodResolver(
  z.object({
    name: z.string().min(1, "Nome é obrigatório"),
    municipality: z.string().min(1, "Município é obrigatório"),
    uf: z.string().min(1, "UF é obrigatório"),
    state_registration: z.string().min(1, "Registro estadual é obrigatório"),
    total_area: z.string().min(1, "Área total é obrigatória"),
    producer: z.object({
      id: z.number().min(1, "Produtor rural é obrigatório"),
    }),
  })
);

onMounted(() => {
  getAllRuralProducers().then(({ data }) => (ruralProducers.value = data));
});

const onFormSubmit = async (e: any) => {
  if (e.valid) {
    createProperty({
      ...e.values,
      producer_id: e.values.producer.id,
    })
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
