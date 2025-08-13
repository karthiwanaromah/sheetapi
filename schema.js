const { z } = require('zod');


const insertRecordSchema = z.object({
    phone: z.union([z.string(), z.number()]).optional(),
    name: z.string().min(1, "Name is required"),
    email: z.string().email("Invalid email format").optional(),
    address: z.string().optional(),
    question1: z.string().optional(),
    question2: z.string().optional(),
    question3: z.string().optional(),
    question4: z.string().optional(),
    question5: z.string().optional(),
    question6: z.string().optional(),
    question7: z.string().optional(),
    question8: z.string().optional(),
    question9: z.string().optional(),
    question10: z.string().optional(),
    finalSuggestedProduct: z.string().optional(),
});

const updateRecordSchema = z.object({
    phone: z.union([z.string(), z.number()]).optional(),
    name: z.string().min(1, "Name is required").optional(),
    email: z.string().email("Invalid email format").optional(),
    address: z.string().optional(),
    question1: z.string().optional(),
    question2: z.string().optional(),
    question3: z.string().optional(),
    question4: z.string().optional(),
    question5: z.string().optional(),
    question6: z.string().optional(),
    question7: z.string().optional(),
    question8: z.string().optional(),
    question9: z.string().optional(),
    question10: z.string().optional(),
    finalSuggestedProduct: z.string().optional(),
});

module.exports = {
    insertRecordSchema,
    updateRecordSchema,
};
