<template>
  <Button label="Criar rebanho" @click="visible = true" />
  <Dialog
    v-model:visible="visible"
    modal
    header="Criar rebanho"
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
        <InputText name="species" type="text" placeholder="Especie" fluid />
        <Message
          v-if="$form.species?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.species?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputNumber name="quantity" placeholder="Quantidade" fluid />
        <Message
          v-if="$form.quantity?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.quantity.error?.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <Select
          name="property_id"
          :options="properties"
          optionLabel="name"
          optionValue="id"
          placeholder="Selecione a propriedade"
          fluid
        />
        <Message
          v-if="$form.property_id?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.property_id?.error?.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <Textarea name="purpose" rows="5" cols="30" style="resize: none" />
        <Message
          v-if="$form.purpose?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.purpose.error?.message }}</Message
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
  InputNumber,
  InputText,
  Message,
  Select,
  Textarea,
  useToast,
} from "primevue";
import { onMounted, ref } from "vue";
import { z } from "zod";
import { createHerd } from "../../../../services/herds";
import { getAllProperties } from "../../../../services/properties";

const visible = ref(false);
const initialValues = ref({
  species: "",
  quantity: 0,
  purpose: "",
  property_id: null,
});
const toast = useToast();
const properties = ref();

const resolver = zodResolver(
  z.object({
    species: z.string().min(1, "Especie é obrigatório"),
    quantity: z.number().min(1, "Quantidade é obrigatória"),
    purpose: z.string().min(1, "Finalidade é obrigatório"),
    property_id: z.number().min(1, "Propriedade é obrigatória"),
  })
);

onMounted(() => {
  getAllProperties().then(({ data }) => (properties.value = data));
});

const onFormSubmit = async (e: any) => {
  if (e.valid) {
    createHerd(e.values)
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
