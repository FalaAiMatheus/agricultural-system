<template>
  <Dialog
    v-model:visible="props.visible"
    modal
    :header="`Editar produtor ${props.producerData?.name}`"
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
          v-model="initialValues.name"
          name="name"
          type="text"
          placeholder="Nome"
          fluid
        />
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
import {
  Button,
  Dialog,
  InputMask,
  InputText,
  Message,
  useToast,
} from "primevue";
import { defineProps, ref, watch } from "vue";
import { z } from "zod";
import { getModifiedFields } from "../../../../core/utils";
import { updateRuralProducer } from "../../../../services/rural-producers";

const props = defineProps({
  visible: {
    type: Boolean,
    required: true,
  },
  handleCloseModal: {
    type: Boolean,
    required: true,
  },
  producerData: {
    type: Object,
    default: null,
  },
});

const initialValues = ref({
  name: "",
  email: "",
  address: "",
  document: "",
  telephone: "",
});
const originalValues = ref({});

watch(
  () => props.producerData,
  (newData) => {
    if (newData) {
      initialValues.value = {
        name: newData.name ?? "",
        email: newData.email ?? "",
        address: newData.address ?? "",
        document: newData.document ?? "",
        telephone: newData.telephone ?? "",
      };
      originalValues.value = { ...initialValues.value };
    }
  },
  { immediate: true }
);
const toast = useToast();

const resolver = zodResolver(
  z.object({
    name: z.string().optional(),
    email: z
      .email({
        error: "Email inválido",
      })
      .optional(),
    address: z.string().optional(),
    document: z.string().optional(),
    telephone: z.string().optional(),
  })
);

const onFormSubmit = async (e) => {
  if (!e.valid) return;

  const modifiedValues = getModifiedFields(e.values, props.producerData);

  if (Object.keys(modifiedValues).length === 0) {
    toast.add({
      severity: "info",
      life: 3000,
      summary: "Nenhuma alteração detectada",
    });
    return;
  }

  try {
    const res = await updateRuralProducer(
      modifiedValues,
      props.producerData.id
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
