const { createServer } = require("http");
const { storage } = require("./storage");
const { insertRecordSchema, updateRecordSchema } = require("./schema");

async function registerRoutes(app) {
    // GET /api/records - Get all records
    app.get("/api/records", async (req, res) => {
        try {
            const records = await storage.getAllRecords();
            res.json({
                success: true,
                data: records,
            });
        } catch (error) {
            console.error("Error fetching records:", error);
            res.status(500).json({
                success: false,
                error: {
                    code: "FETCH_ERROR",
                    message: "Failed to fetch records",
                    details: error instanceof Error ? error.message : "Unknown error",
                },
            });
        }
    });

    // GET /api/records/:id - Get single record
    app.get("/api/records/:id", async (req, res) => {
        try {
            const id = parseInt(req.params.id);
            if (isNaN(id)) {
                return res.status(400).json({
                    success: false,
                    error: {
                        code: "INVALID_ID",
                        message: "Invalid record ID",
                        details: "ID must be a number",
                    },
                });
            }

            const record = await storage.getRecord(id);
            if (!record) {
                return res.status(404).json({
                    success: false,
                    error: {
                        code: "RECORD_NOT_FOUND",
                        message: "Record not found",
                        details: `No record found with ID ${id}`,
                    },
                });
            }

            res.json({
                success: true,
                data: record,
            });
        } catch (error) {
            console.error("Error fetching record:", error);
            res.status(500).json({
                success: false,
                error: {
                    code: "FETCH_ERROR",
                    message: "Failed to fetch record",
                    details: error instanceof Error ? error.message : "Unknown error",
                },
            });
        }
    });


    app.get("/api/records/phone/:phone", async (req, res) => {
        try {
            const phone = req.params.phone;
            if (!phone) {
                return res.status(400).json({
                    success: false,
                    error: {
                        code: "INVALID_PHONE",
                        message: "Phone number is required",
                        details: "Phone number must be provided",
                    },
                });
            }

            const record = await storage.getRecordByPhone(phone);
            if (!record) {
                return res.status(404).json({
                    success: false,
                    error: {
                        code: "RECORD_NOT_FOUND",
                        message: "Record not found",
                        details: `No record found with phone ${phone}`,
                    },
                });
            }

            res.json({
                success: true,
                data: record,
            });
        } catch (error) {
            console.error("Error fetching record by phone:", error);
            res.status(500).json({
                success: false,
                error: {
                    code: "FETCH_ERROR",
                    message: "Failed to fetch record by phone",
                    details: error instanceof Error ? error.message : "Unknown error",
                },
            });
        }
    })

    // POST /api/records - Create new record
    app.post("/api/records", async (req, res) => {
        try {
            const validationResult = insertRecordSchema.safeParse(req.body);
            console.log("Validation Result:", validationResult);
            if (!validationResult.success) {
                return res.status(400).json({
                    success: false,
                    error: {
                        code: "VALIDATION_ERROR",
                        message: "Invalid record data",
                        details: validationResult.error.errors,
                    },
                });
            }

            const record = await storage.createRecord(validationResult.data);
            res.status(201).json({
                success: true,
                message: "Record created successfully",
                data: record,
            });
        } catch (error) {
            console.error("Error creating record:", error);
            res.status(500).json({
                success: false,
                error: {
                    code: "CREATE_ERROR",
                    message: "Failed to create record",
                    details: error instanceof Error ? error.message : "Unknown error",
                },
            });
        }
    });


    // PUT /api/records/:id - Update record
    app.put("/api/records/:id", async (req, res) => {
        try {
            const id = parseInt(req.params.id);
            if (isNaN(id)) {
                return res.status(400).json({
                    success: false,
                    error: {
                        code: "INVALID_ID",
                        message: "Invalid record ID",
                        details: "ID must be a number",
                    },
                });
            }

            const validationResult = updateRecordSchema.safeParse(req.body);
            if (!validationResult.success) {
                return res.status(400).json({
                    success: false,
                    error: {
                        code: "VALIDATION_ERROR",
                        message: "Invalid update data",
                        details: validationResult.error.errors,
                    },
                });
            }

            const updatedRecord = await storage.updateRecord(id, validationResult.data);
            if (!updatedRecord) {
                return res.status(404).json({
                    success: false,
                    error: {
                        code: "RECORD_NOT_FOUND",
                        message: "Record not found",
                        details: `No record found with ID ${id}`,
                    },
                });
            }

            res.json({
                success: true,
                message: "Record updated successfully",
                data: updatedRecord,
            });
        } catch (error) {
            console.error("Error updating record:", error);
            res.status(500).json({
                success: false,
                error: {
                    code: "UPDATE_ERROR",
                    message: "Failed to update record",
                    details: error instanceof Error ? error.message : "Unknown error",
                },
            });
        }
    });

    // DELETE /api/records/:id - Delete record
    app.delete("/api/records/:id", async (req, res) => {
        try {
            const id = parseInt(req.params.id);
            if (isNaN(id)) {
                return res.status(400).json({
                    success: false,
                    error: {
                        code: "INVALID_ID",
                        message: "Invalid record ID",
                        details: "ID must be a number",
                    },
                });
            }

            const deleted = await storage.deleteRecord(id);
            if (!deleted) {
                return res.status(404).json({
                    success: false,
                    error: {
                        code: "RECORD_NOT_FOUND",
                        message: "Record not found",
                        details: `No record found with ID ${id}`,
                    },
                });
            }

            res.json({
                success: true,
                message: "Record deleted successfully",
            });
        } catch (error) {
            console.error("Error deleting record:", error);
            res.status(500).json({
                success: false,
                error: {
                    code: "DELETE_ERROR",
                    message: "Failed to delete record",
                    details: error instanceof Error ? error.message : "Unknown error",
                },
            });
        }
    });

    const httpServer = createServer(app);
    return httpServer;
}

module.exports = { registerRoutes };
