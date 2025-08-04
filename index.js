const express = require('express');
const cors = require('cors');
require('dotenv').config();

const { registerRoutes } = require('./routes');
const { GoogleSheetsService } = require('./googleSheetsService');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors([
    '*'
]));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Initialize Google Sheets service
const initializeApp = async () => {
    try {
        const sheetsService = new GoogleSheetsService();
        await sheetsService.initializeSheet();
        console.log('Google Sheets initialized successfully');

        // Register routes
        const server = await registerRoutes(app);

        server.listen(PORT, () => {
            console.log(`Server running on port ${PORT}`);
            console.log(`API endpoints available at http://localhost:${PORT}/api/records`);
        });
    } catch (error) {
        console.error('Failed to initialize application:', error);
        process.exit(1);
    }
};

initializeApp();