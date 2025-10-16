export const getModifiedFields = (current: any, original: any) => {
  const modified: Record<string, any> = {};
  const currentValues = current.value ?? current;
  const originalValues = original.value ?? original;

  Object.keys(currentValues).forEach((key) => {
    if (currentValues[key] !== originalValues[key]) {
      modified[key] = currentValues[key];
    }
  });

  return modified;
};
