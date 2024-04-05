
import { GoogleGenerativeAI } from "@google/generative-ai";

export default class Gemini {
    constructor({ ...args }) {
        this.lastPrompt = "";
        this.lastResponse = "";
        this.modelName = args.modelName || "gemini-pro";
        this.API_KEY = args.key;
        this.AI = new GoogleGenerativeAI(this.API_KEY);

        const generationConfig = {
            // stopSequences: ["red"],
            // maxOutputTokens: 100,
            temperature: args.temperature || .9,
            // topP: 0.1,
            //  topK: 16,
        };
        console.log(generationConfig);

        this.MODEL = this.AI.getGenerativeModel({ model: this.modelName, generationConfig });
    }

    async execute(prompt, history) {
        if (prompt == "") {
            prompt = this.lastPrompt;
        } else {
            this.lastPrompt = prompt;
        }

        if (history.length > 0) {
            const chat = this.MODEL.startChat({
                history: history,
                generationConfig: {
                    maxOutputTokens: 100,
                },
            });
            const result = await chat.sendMessage(prompt);
            const response = await result.response;
            return response.text();
        } else {
            const result = await this.MODEL.generateContent(prompt);
            const response = await result.response;
            return response.text();
        }
    }
}