<template>
  <Dialog
    v-model:visible="props.visible"
    modal
    :header="`Editar propriedade ${props.productionUnitData?.culture_name}`"
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
          v-model="initialValues.culture_name"
          name="culture_name"
          type="text"
          placeholder="Nome"
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
        <InputText name="latitude" type="text" placeholder="Latitude" fluid />
        <Message
          v-if="$form.latitude?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.latitude?.error.message }}</Message
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
      <div class="flex justify-end gap-2">
        <Button
          type="button"
          label="Cancelar"
          severity="secondary"
          @click="props.handleCloseModal"
        ></Button>
        <Button type="submit" label="Criar"></Button>
      </div>
    </Form>
  </Dialog>
</template>

<script setup>
import { Form } from "@primevue/forms";
import { zodResolver } from "@primevue/forms/resolvers/zod";
import { Button, Dialog, InputText, Message, useToast } from "primevue";
import { defineProps, onMounted, ref, watch } from "vue";
import { z } from "zod";
import { getModifiedFields } from "../../../../core/utils";
import { updateProductionUnit } from "../../../../services/production-units";
import { getAllProperties } from "../../../../services/properties";

const props = defineProps({
  visible: {
    type: Boolean,
    required: true,
  },
  handleCloseModal: {
    type: Boolean,
    required: true,
  },
  productionUnitData: {
    type: Object,
    default: null,
  },
});
const productionUnit = ref();

const initialValues = ref({
  culture_name: "",
  total_area_ha: "",
  latitude: "",
  longitude: "",
});
const originalValues = ref({});
const properties = ref();

watch(
  () => props.productionUnitData,
  (newData) => {
    if (newData) {
      initialValues.value = {
        culture_name: newData.culture_name ?? "",
        total_area_ha: newData.total_area_ha ?? "",
        latitude: newData.latitude ?? "",
        longitude: newData.longitude ?? "",
      };
      originalValues.value = { ...initialValues.value };
    }
  },
  { immediate: true }
);
const toast = useToast();

const resolver = zodResolver(
  z.object({
    culture_name: z.string().optional(),
    total_area_ha: z.string().optional(),
    latitude: z.string().optional(),
    longitude: z.string().optional(),
  })
);

onMounted(() => {
  getAllProperties().then(({ data }) => (properties.value = data));
});

const onFormSubmit = async (e) => {
  if (!e.valid) return;

  const modifiedValues = getModifiedFields(e.values, props.productionUnitData);
  console.log(modifiedValues);

  if (Object.keys(modifiedValues).length === 0) {
    toast.add({
      severity: "info",
      life: 3000,
      summary: "Nenhuma alteração detectada",
    });
    return;
  }

  try {
    const res = await updateProductionUnit(
      modifiedValues,
      props.productionUnitData.id
    );
    toast.add({
      severity: "success",
      life: 3000,
      summary: res.message,
    });
    props.handleCloseModal();
  } catch (error) {
    if (error instanceof Error) {
      toast.add({
        severity: "error",
        life: 3000,
        summary: error.message,
      });
    }
  }
};
</script>
