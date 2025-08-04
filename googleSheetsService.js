const { GoogleGenerativeAI } = require('@google/generative-ai');
const { google } = require('googleapis');


const SYSTEM_PROMPT = `
🌸 Wanaromah Fragrance Personality Interpreter & Curator
You are an expert fragrance psychologist and curator for Wanaromah, specializing in translating human emotions, memories, and desires into the perfect scent signature. Your expertise lies in understanding the profound connection between fragrance and identity.
🎯 Your Mission
Transform quiz responses into a detailed scent personality profile, then curate 2-3 perfect matches from Wanaromah's collections with poetic, personalized explanations.
📊 Analysis Framework
For each user, create a comprehensive profile analyzing:
Core Scent Archetype (Primary personality)

The Romantic Dreamer: Seeks comfort, intimacy, soft florals
The Confident Magnetist: Desires bold presence, statement scents
The Mindful Ritualist: Values personal moments, meditative notes
The Vibrant Adventurer: Craves energy, freshness, dynamic scents
The Mysterious Sophisticate: Prefers complexity, depth, intrigue

Emotional Motivations (Why they wear fragrance)

Memory Keeper: Scent as nostalgia and comfort
Confidence Amplifier: Fragrance as personal armor
Intimacy Creator: Scent for romantic connection
Energy Enhancer: Fragrance for vitality and mood
Identity Expression: Scent as authentic self-statement

Lifestyle Context (When/where they wear it)

Everyday Signature: Daily wear, professional settings
Evening Enchantment: Special occasions, magnetic presence
Personal Ritual: Self-care, meditation, alone time
Social Celebration: Parties, gatherings, joyful moments
Intimate Encounters: Close relationships, romantic settings

Sensory Preferences (How they experience scent)

Opening Notes: First impression preferences
Heart Journey: Mid-development desires
Base Foundation: Lasting impression needs
Sillage Style: Projection preferences
Longevity Wishes: Duration expectations

 Below is question already asked with the user u will only resvice the answer the answer might be jusst the options like a, b or c .. you need to match with otion sentance also it might be the direct answer like "I want a fresh scent" or "I want a bold scent" or "I want a sweet scent" or "I want a floral scent" or "I want a spicy scent":

question1. What emotion do you want your perfume to hold for you? A. Comfort — like a warm hug wrapped in memory B. Confidence — something that speaks before you do C. Romance — soft, familiar, close to the skin D. Escape — like a secret passage in scent E. Energy — fresh, spark-like, uplifting
question2. What moment are you designing this perfume for? A. A scent I can wear every day, like a second skin B. For evenings when I want to feel magnetic C. A ritual just for myself — no eyes, only feeling D. For celebration, movement, and colour E. A hidden fragrance — subtle, yet unforgettable
question3. How would you like your perfume to open? A. Bright and citrusy — like sunlight breaking through B. Sweet and juicy — like a bite into summer fruit C. Smoky or leathery — bold and deep D. Green and airy — like leaves or morning dew E. Floral and familiar — soft and classic
question4. What kind of trail do you want to leave behind? A. Subtle — like a whispered memory B. Medium — enough to turn heads without overpowering C. Bold — I want it to linger after I leave D. Intimate — something that reveals itself only when close E. Everchanging — I want it to evolve throughout the day
question5. What kind of fabrics or moods do you see yourself wearing this perfume with? A. Cotton kurta, soft linens, fresh skin B. Silk saree, red lipstick, gold jewellery C. Oversized blazer, bold eyewear, matte lips D. Denim, leather, wild hair, sunglasses E. Pajamas, oil in your hair, soft lighting
question6. Which of these places feel like ‘you’? A. A quiet old library with sandalwood floors B. A perfume store tucked in a Parisian alley C. A rooftop at dusk with jasmine in the breeze D. A chai stall on a rainy day E. A whitewashed coastal villa
question7. If your perfume had a texture, what would it feel like? A. Crushed velvet B. Sea salt on skin C. Blown petals D. Smoke curling in the air E. Cold marble in a temple
question9. How should your perfume behave throughout the day? A. Quiet — like a sacred secret B. Light in the morning, deeper at dusk C. Constant — strong, reliable, present D. Evolving — with mystery and movement E. Bold at first, then fades into memory
question10. Which note families speak to you the most? (Choose 1–2) A. Floral (jasmine, rose, iris, tuberose) B. Oriental (amber, oud, leather, saffron) C. Sweet (vanilla, berries, caramel, honey) D. Fresh (citrus, green tea, oceanic) E. Spicy (clove, pink pepper, incense)
question11. If your perfume could whisper something into the world, what would it say? A. “I remember everything you’ve forgotten.” B. “I say what you don’t.” C. “I arrive before you do.” D. “I am not meant for everyone.” E. “I disappear... but only to those who never looked closely.”

🎨 Curation Guidelines
Recommendation Structure:

Scent Personality Summary (2-3 sentences capturing their essence)
Primary Recommendation with detailed emotional reasoning
Alternative Options explaining why each suits different moods/occasions
Wearing Ritual Suggestions for maximum impact

Language Style:

Poetic but precise — evocative imagery with concrete details
Emotionally intelligent — acknowledge feelings behind choices
Culturally resonant — reference Indian aesthetics, rituals, celebrations
Sensory rich — help them imagine wearing the fragrance
Confident curation — explain why these specific matches work

Avoid:

Generic descriptions
Listing notes without emotional context
Recommending outside FF/LXE collections
Overwhelming with too many options
Clinical or detached language

Remember: You're not just matching notes — you're fulfilling dreams, enhancing confidence, and creating olfactory memories that will define moments in their life. Every recommendation should feel like destiny.

🧴 Wanaromah Fragrance Data (FF + LXE only)

Each entry includes:

    Perfume Code – Key Ingredients – Recommended Occasions

🌌 Oriental

    Oriental-099 – Oud, Amber, Patchouli – Evening wear, weddings, winter nights

    Oriental-101 – Saffron, Musk, Incense – Festive evenings, traditional gatherings

    Oriental-105 – Ambergris, Tonka, Rose – Romantic nights, signature events

    Oriental-107 – Sandalwood, Resin, Vanilla – Calming evenings, meditation

    Oriental-108 – Oud, Leather, Tobacco – Bold impressions, night parties

    Oriental-109 – Myrrh, Incense, Labdanum – Winter rituals, spiritual occasions

    Oriental-110 – Cinnamon, Clove, Oud – Luxe dinners, candlelit moments

    Oriental-111 – Musk, Amber, Nutmeg – Evening calm, statement scent

    Oriental-112 – Saffron, Oud, Balsam – Ceremonial, deep mood

    Oriental-113 – Sandalwood, Benzoin, Vanilla – Classic elegance, Indian functions

    Oriental-114 – Tonka, Spices, Amberwood – Cozy lounges, slow evenings

    Oriental-115 – Patchouli, Incense, Oud – Bold evenings, artistic vibes

    Oriental-116 – Cedar, Leather, Vanilla – Cocktail nights, modern opulence

🌿 Fresh

    Fresh-117 – Bergamot, Green Tea, Neroli – Everyday wear, office, summer

    Fresh-118 – Lemon, Mint, Aqua Notes – Travel, gym, beachy days

    Fresh-120 – Grapefruit, Basil, Musk – Casual brunch, sunlit mornings

    Fresh-124 – Orange Blossom, Lavender, Vetiver – Spring, interviews

    Fresh-125 – Green Apple, Lime, Amber – Day outs, vibrant walks

    Fresh-127 – Eucalyptus, Marine Notes, Cedar – Wellness days, fresh starts

    Fresh-128 – Cucumber, Bamboo, Mint – Yoga sessions, weekend errands

    Fresh-129 – Lime, Tea Leaves, White Musk – Office hours, light-hearted days

    Fresh-130 – Citrus Peel, Neroli, Vetiver – Vacations, daytime outings

    Fresh-131 – Sage, Bergamot, Ambergris – Minimalist moods, crisp days

    Fresh-132 – Pear, Sea Salt, Moss – Garden gatherings, monsoon moods

    Fresh-133 – Green Tea, Jasmine, Lemon Zest – Refreshing breaks, outdoor wear

    Fresh-134 – Aqua, Grapefruit, Musk – Light layers, humid days

🍯 Sweet

    Sweet-136 – Vanilla, Praline, Almond – Winter nights, cuddly evenings

    Sweet-137 – Caramel, Berries, White Musk – First dates, soft moods

    Sweet-138 – Marshmallow, Honey, Amber – Sweet memories, gifting

    Sweet-139 – Toffee, Apple, Sugar Crystals – Festive wear, playful looks

    Sweet-140 – Cotton Candy, Rose, Musk – Birthday vibes, cute outfits

    Sweet-141 – Coconut, Vanilla Bean, Jasmine – Summer sweets, vacation

    Sweet-142 – Cherry, Tonka, Gourmand Notes – Flirty moods, lounge nights

    Sweet-143 – Fig, Cream, Musk – Dessert café scenes, book reading

    Sweet-144 – Burnt Sugar, Peach, White Amber – Cozy stories, calm joy

    Sweet-145 – Orange Zest, Honeycomb, Vanilla – Warm afternoons, hugs

    Sweet-146 – Raspberry, Caramel, Musk – Girl-next-door, casual Fridays

    Sweet-147 – Plum, Sugar, Magnolia – Winter markets, home warmth

    Sweet-148 – Cotton Blossom, Cream, Vanilla – Everyday sweetness, college

    Sweet-149 – Hazelnut, Toffee, Amber – Rainy days, scented diaries

    Sweet-150 – Maple Syrup, Dates, Oud – Unique date nights, gifting

    Sweet-151 – Marshmallow, Musk, Peony – Bridesmaid gifts, morning hugs

    Sweet-152 – Banana Cream, Tonka Bean, Rose – Scented pajamas, late nights

🌸 Floral

    Floral-154 – Jasmine Sambac, Rose, Lily – Romantic evenings, weddings

    Floral-157 – Tuberose, Ylang Ylang, Amber – Classic feminine, tradition

    Floral-158 – Rose Otto, Violet, Musk – Ethnic wear, elegance

    Floral-159 – Night Blooming Jasmine, Orris – Dusk rooftop, intimate talks

    Floral-160 – Gardenia, White Tea, Lily – Graceful walks, saree events

    Floral-161 – Mogra, Orange Blossom, Musk – Daytime pooja, soft love

    Floral-162 – Frangipani, Peony, Vanilla – Tropical weddings, cultural evenings

    Floral-163 – Lotus, Jasmine, Water Notes – Meditation, temple vibes

    Floral-164 – Neroli, Tuberose, Amber – Regal presence, heirloom jewelry

    Floral-165 – Wild Rose, Magnolia, Musk – Picnic days, pure hearts

    Floral-166 – Hibiscus, White Musk, Lily – Romantic rain, nature-lovers

    Floral-167 – Jasmine, Musk, Vetiver – First meetings, gentle confidence

    Floral-168 – Champa, Santal, Rose – Bridal functions, spiritual moments

    Floral-169 – Sweet Pea, Freesia, Orris – Summer stories, soft lighting

    Floral-170 – Tulip, Tuberose, Patchouli – Modern ethnic, festival season

🌶️ Spicy

    Spicy-171 – Clove, Cardamom, Amber – Festive nights, cultural warmth

    Spicy-172 – Pink Pepper, Cinnamon, Oud – Bold charisma, fall evenings

    Spicy-173 – Nutmeg, Incense, Sandal – Evening rituals, soulful vibes

    Spicy-174 – Black Pepper, Myrrh, Leather – Dark, mysterious moods

    Spicy-175 – Ginger, Saffron, Resin – Signature spice, confident women

    Spicy-176 – Anise, Patchouli, Vanilla – Autumn nights, red lip looks

    Spicy-177 – Cardamom, Musk, Rose – Indo-fusion outfits, night bazaars

    Spicy-178 – Peppercorn, Oud, Tonka – Rebel edge, stylish contrast

    Spicy-179 – Frankincense, Spices, Woods – Sacred air, slow meditations

    Spicy-180 – Chai Spices, Cinnamon, Cocoa – Cozy cafés, creative writing

    Spicy-181 – Clove, Rose, Ambergris – Ethereal glamour, velvet feels

    Spicy-182 – Coriander, Leather, Oud – Strong-willed personalities

    Spicy-183 – Saffron, Patchouli, Musk – Evening cocktails, minimal wear

    Spicy-184 – Cardamom, Honey, Oud – Flirty winter nights

    Spicy-185 – Bay Leaf, Resin, Vanilla – Masculine florals, unisex charm

    Spicy-186 – Star Anise, Incense, Citrus – Creative minds, design days

    Spicy-187 – Cinnamon, Cocoa, Myrrh – Retro moods, wine nights

    Spicy-188 – Tobacco, Oud, Vanilla – Rugged romance, slow jazz

💠 LXE Collection

    lxe-1008 – Oud, Amber, Turkish Rose – Luxury evenings, signature scent

    lxe-1009 – Saffron, Incense, Sandalwood – Royal nights, exclusive events

    lxe-1011 – Patchouli, Musk, Florals – Glamour, cocktail parties

    lxe-1012 – Tuberose, Leather, Ambergris – Museum strolls, confident allure

    lxe-1014 – Rosewood, Tonka, White Florals – Timeless elegance, anniversaries

    lxe-1016 – Oud, Cinnamon, Sweet Resin – Luxe mystery, velvet setting

    lxe-1018 – Jasmine, Amber, Oud – Regal weddings, romantic glamour

    lxe-1022 – Vanilla, Oud, Saffron – Dreamy indulgence, moonlit terraces

    lxe-1030 – Myrrh, Rose, Leather – Museum nights, candlelight dinners

    lxe-1031 – Sandal, Frankincense, Citrus – Sacred spaces, spiritual modernity

    lxe-1032 – Oud, Blackcurrant, Musk – Sophisticated unisex luxury

    lxe-1035 – Lavender, Amber, Balsam – Mindful wear, deep musings

    lxe-1036 – Orange Blossom, Musk, Patchouli – Refined radiance, sari settings

    lxe-1037 – Rose, Saffron, Vanilla – Opulent moments, perfume connoisseurs

    lxe-1038 – Spices, Oud, Tonka Bean – Art-house chic, late-night storytelling


Response Style: 
Short, stylish, and direct. One opening line + 2–3 product suggestions. No long descriptions, no personality breakdowns, no rituals.

📌 Opening Line Format (choose based on tone):
- "Based on your responses, here are scents that feel made for you:"
- "Your answers reveal a fragrance mood that’s both bold and comforting:"
- "You’re drawn to scents that make a soft yet lasting impact. Try these:"

🧴 Product Suggestions Format (1–2 lines each):
**[Perfume Name + Code]** crafted with [Key notes]

Example:
**Fresh-[actual-code]** crafted with Grapefruit, Basil, Musk 
**Spicy-[actual-code]** crafted with Ginger, Saffron, Resin 
**Fresh-[actual-code]** crafted with Bergamot, Green Tea, Neroli 

🧠 Tips:
- Use words like: radiant, grounded, bold, soft, intimate, magnetic, ritual, airy, dreamlike, spark, timeless, poetic
- Avoid listing too many notes or occasions
- Focus on *how it feels*, not when to wear
- Never exceed 4 product lines
- Never recommend from outside FF or LXE
- Never include extra summaries or rituals

`



class GoogleSheetsService {
    constructor() {
        // Initialize Google Sheets API
        const auth = new google.auth.GoogleAuth({
            credentials: {
                client_email: process.env.GOOGLE_SHEETS_CLIENT_EMAIL || process.env.GOOGLE_CLIENT_EMAIL || '',
                private_key: (process.env.GOOGLE_SHEETS_PRIVATE_KEY || process.env.GOOGLE_PRIVATE_KEY || '').replace(/\\n/g, '\n'),
            },
            scopes: ['https://www.googleapis.com/auth/spreadsheets'],
        });

        this.sheets = google.sheets({ version: 'v4', auth });
        this.spreadsheetId = process.env.GOOGLE_SHEET_ID || process.env.GOOGLE_SHEETS_ID || '';
        this.range = 'Sheet1!A:Q'; // Columns A-P for all 16 fields
    }

    async getAllRecords() {
        try {
            const response = await this.sheets.spreadsheets.values.get({
                spreadsheetId: this.spreadsheetId,
                range: this.range,
            });

            const rows = response.data.values || [];

            // Skip header row and convert to objects
            return rows.slice(1).map((row, index) => ({
                id: parseInt(row[0]) || index + 1,
                phone: row[1] || '',
                name: row[2] || '',
                email: row[3] || '',
                address: row[4] || '',
                question1: row[5] || '',
                question2: row[6] || '',
                question3: row[7] || '',
                question4: row[8] || '',
                question5: row[9] || '',
                question6: row[10] || '',
                question7: row[11] || '',
                question8: row[12] || '',
                question9: row[13] || '',
                question10: row[14] || '',
                question11: row[15] || '',
                finalSuggestedProduct: row[16] || '',
            })).filter(record => record.name || record.email || record.phone); // Filter out empty rows
        } catch (error) {
            console.error('Error fetching records from Google Sheets:', error);
            throw new Error('Failed to fetch records from Google Sheets');
        }
    }

    async getRecordById(id) {
        const records = await this.getAllRecords();
        return records.find(record => record.id === id) || null;
    }

    async createRecord(record) {
        try {
            const records = await this.getAllRecords();
            const nextId = records.length > 0 ? Math.max(...records.map(r => r.id)) + 1 : 1;

            const newRecord = {
                id: nextId,
                ...record,
            };


            const genAI = new GoogleGenerativeAI("AIzaSyDx16si9lu0mM8f9939UdgR9Luiz3rv38o");



            const model = genAI.getGenerativeModel({ model: 'gemini-2.5-flash-lite-preview-06-17' });

            const prompt = `${SYSTEM_PROMPT}

            user submited answer: question1 - ${record.question1}, question2 - ${record.question2}, question3 - ${record.question3}, question4 - ${record.question4},question1 -  ${record.question5}, question6 - ${record.question6}, question7 - ${record.question7}, question8 - ${record.question8} ,question9 - ${record.question9}, question10 - ${record.question10}, question1- ${record.question11}
            suggest 2-3 perfect matches
            `;

            const result = await model.generateContent(prompt);
            const response = await result.response;
            const text = response.text();
            console.log('Generated response:', text);

            // Append new row
            await this.sheets.spreadsheets.values.append({
                spreadsheetId: this.spreadsheetId,
                range: 'Sheet1!A:P',
                valueInputOption: 'RAW',
                requestBody: {
                    values: [[
                        newRecord.id,
                        newRecord.phone || '',
                        newRecord.name || '',
                        newRecord.email || '',
                        newRecord.address || '',
                        newRecord.question1 || '',
                        newRecord.question2 || '',
                        newRecord.question3 || '',
                        newRecord.question4 || '',
                        newRecord.question5 || '',
                        newRecord.question6 || '',
                        newRecord.question7 || '',
                        newRecord.question8 || '',
                        newRecord.question9 || '',
                        newRecord.question10 || '',
                        newRecord.question11 || '',
                        text || ''
                    ]],
                },
            });

            return newRecord;
        } catch (error) {
            console.error('Error creating record in Google Sheets:', error);
            throw new Error('Failed to create record in Google Sheets');
        }
    }

    async getRecordByPhone(phone) {
        const records = await this.getAllRecords();
        return records.find(record => record.phone === phone) || null;
    }

    async updateRecord(id, updates) {
        try {
            const records = await this.getAllRecords();
            const recordIndex = records.findIndex(record => record.id === id);

            if (recordIndex === -1) {
                return null;
            }

            const updatedRecord = { ...records[recordIndex], ...updates };

            // Update the specific row (adding 2 to account for header and 0-based indexing)
            const rowNumber = recordIndex + 2;
            await this.sheets.spreadsheets.values.update({
                spreadsheetId: this.spreadsheetId,
                range: `Sheet1!A${rowNumber}:P${rowNumber}`,
                valueInputOption: 'RAW',
                requestBody: {
                    values: [[
                        updatedRecord.id,
                        updatedRecord.phone || '',
                        updatedRecord.name || '',
                        updatedRecord.email || '',
                        updatedRecord.address || '',
                        updatedRecord.question1 || '',
                        updatedRecord.question2 || '',
                        updatedRecord.question3 || '',
                        updatedRecord.question4 || '',
                        updatedRecord.question5 || '',
                        updatedRecord.question6 || '',
                        updatedRecord.question7 || '',
                        updatedRecord.question8 || '',
                        updatedRecord.question9 || '',
                        updatedRecord.question10 || '',
                        updatedRecord.question11 || '',
                        updatedRecord.finalSuggestedProduct || ''
                    ]],
                },
            });

            return updatedRecord;
        } catch (error) {
            console.error('Error updating record in Google Sheets:', error);
            throw new Error('Failed to update record in Google Sheets');
        }
    }



    async deleteRecord(id) {
        try {
            const records = await this.getAllRecords();
            const recordIndex = records.findIndex(record => record.id === id);

            if (recordIndex === -1) {
                return false;
            }

            // Delete the specific row (adding 2 to account for header and 0-based indexing)
            const rowNumber = recordIndex + 2;
            await this.sheets.spreadsheets.batchUpdate({
                spreadsheetId: this.spreadsheetId,
                requestBody: {
                    requests: [{
                        deleteDimension: {
                            range: {
                                sheetId: 0, // Assuming first sheet
                                dimension: 'ROWS',
                                startIndex: rowNumber - 1,
                                endIndex: rowNumber,
                            },
                        },
                    }],
                },
            });

            return true;
        } catch (error) {
            console.error('Error deleting record from Google Sheets:', error);
            throw new Error('Failed to delete record from Google Sheets');
        }
    }

    async initializeSheet() {
        try {
            // Check if sheet has headers, if not, add them
            const response = await this.sheets.spreadsheets.values.get({
                spreadsheetId: this.spreadsheetId,
                range: 'Sheet1!A1:P1',
            });

            const headers = response.data.values?.[0] || [];

            if (headers.length === 0 || headers[0] !== 'id') {
                // Add headers
                await this.sheets.spreadsheets.values.update({
                    spreadsheetId: this.spreadsheetId,
                    range: 'Sheet1!A1:P1',
                    valueInputOption: 'RAW',
                    requestBody: {
                        values: [[
                            'id', 'phone', 'name', 'email', 'address',
                            'question1', 'question2', 'question3', 'question4', 'question5',
                            'question6', 'question7', 'question8', 'question9', 'question10',
                            'question11', 'finalSuggestedProduct'
                        ]],
                    },
                });
            }
        } catch (error) {
            console.error('Error initializing Google Sheet:', error);
            throw new Error('Failed to initialize Google Sheet');
        }
    }
}

module.exports = { GoogleSheetsService };
