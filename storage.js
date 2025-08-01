const { GoogleSheetsService } = require('./googleSheetsService');

class Storage {
    constructor() {
        this.sheetsService = new GoogleSheetsService();
    }

    async getAllRecords() {
        return await this.sheetsService.getAllRecords();
    }

    async getRecord(id) {
        return await this.sheetsService.getRecordById(id);
    }

    async createRecord(record) {
        return await this.sheetsService.createRecord(record);
    }

    async updateRecord(id, updates) {
        return await this.sheetsService.updateRecord(id, updates);
    }

    async getRecordByPhone(phone) {
        return await this.sheetsService.getRecordByPhone(phone);
    }

    async deleteRecord(id) {
        return await this.sheetsService.deleteRecord(id);
    }
}

const storage = new Storage();
module.exports = { storage };
