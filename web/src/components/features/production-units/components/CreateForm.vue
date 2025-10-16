<template>
  <Button label="Criar unidade de produção" @click="visible = true" />
  <Dialog
    v-model:visible="visible"
    modal
    header="Criar unidade de produção"
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
        <InputText
          name="culture_name"
          type="text"
          placeholder="Nome da cultura"
          fluid
        />
        <Message
          v-if="$form.culture_name?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.culture_name?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <Select
          name="property.id"
          :options="properties"
          optionLabel="name"
          optionValue="id"
          placeholder="Selecione a propriedade"
          fluid
        />
        <Message
          v-if="$form.property?.id?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.property?.id?.error?.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputText
          name="total_area_ha"
          type="text"
          placeholder="Area total por hectares"
          fluid
        />
        <Message
          v-if="$form.total_area_ha?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.total_area_ha?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputText name="longitude" type="text" placeholder="Longitude" fluid />
        <Message
          v-if="$form.longitude?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.longitude?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputText name="latitude" type="text" placeholder="Latitude" fluid />
        <Message
          v-if="$form.latitude?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.latitude?.error.message }}</Message
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

<script setup>
import { Form } from "@primevue/forms";
import { zodResolver } from "@primevue/forms/resolvers/zod";
import { Button, Dialog, InputText, Message, Select, useToast } from "primevue";
import { onMounted, ref } from "vue";
import { z } from "zod";
import { createProductionUnit } from "../../../../services/production-units";
import { getAllProperties } from "../../../../services/properties";

const visible = ref(false);
const initialValues = ref({
  culture_name: "",
  total_area_ha: "",
  latitude: "",
  longitude: "",
  property: { id: "" },
});
const toast = useToast();
const properties = ref();

const resolver = zodResolver(
  z.object({
    culture_name: z.string().min(1, "Nome da cultura é obrigatório"),
    total_area_ha: z.string().min(1, "Area total por hectares é obrigatória"),
    latitude: z.string().min(1, "Latitude é obrigatória"),
    longitude: z.string().min(1, "Longitude é obrigatória"),
    property: z.object({
      id: z.number().min(1, "Propriedade é obrigatória"),
    }),
  })
);

onMounted(() => {
  getAllProperties().then(({ data }) => (properties.value = data));
});

const onFormSubmit = async (e) => {
  if (e.valid) {
    createProductionUnit({
      ...e.values,
      property_id: e.values.property.id,
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
