<?php
// Replace with your actual Gemini API key
$apiKey = 'AIzaSyDx16si9lu0mM8f9939UdgR9Luiz3rv38o';

$responseText = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $q1 = $_POST['q1'] ?? [];
    $q2 = $_POST['q2'] ?? [];
    $q3 = $_POST['q3'] ?? [];
    $q4 = $_POST['q4'] ?? [];
    $q5 = $_POST['q5'] ?? '';

    // Validate that all fields have at least one selection
    if (empty($q1) || empty($q2) || empty($q3) || empty($q4) || empty($q5)) {
        $errorMessage = "Please make at least one selection for each question.";
    } else {
        $personalities = [
            "casual_enthusiast" => "Write like a casual shopper who's genuinely excited but uses everyday language",
            "sophisticated_critic" => "Write like someone with refined taste who appreciates quality details",
            "busy_professional" => "Write like a busy person who values efficiency and results",
            "gift_buyer" => "Write like someone who was shopping for a gift and had a specific mission",
            "fragrance_newbie" => "Write like someone new to luxury fragrances who was pleasantly surprised",
            "loyal_customer" => "Write like someone who has experience with perfume shops and can compare",
            "skeptical_convert" => "Write like someone who was initially unsure but was won over"
        ];

        $writingStyles = [
            "Start with a specific detail or moment from the visit",
            "Begin with an unexpected observation or feeling",
            "Open with a comparison to past experiences",
            "Start with what you weren't expecting",
            "Begin with a question or statement that draws readers in",
            "Open with a sensory description",
            "Start with the outcome/result first, then explain why"
        ];

        $randomPersonality = array_rand($personalities);
        $randomStyle = $writingStyles[array_rand($writingStyles)];

        // Convert arrays to strings for the prompt
        $q1_text = implode(', ', $q1);
        $q2_text = implode(', ', $q2);
        $q3_text = implode(', ', $q3);
        $q4_text = implode(', ', $q4);

        $prompt = "You are writing a Google review for Wanaromah (luxury perfume store since 1933). 

**CRITICAL REQUIREMENTS:**
- Write as a '" . $personalities[$randomPersonality] . "'
- " . $randomStyle . "
- Use completely different vocabulary, sentence structure, and tone from typical reviews
- NO clichés like 'thrilled', 'delighted', 'absolutely', 'wonderful experience', 'highly recommend'
- Write 60-90 words maximum
- Sound like a real person, not marketing copy
- Each review must feel like it's from a different human being
- Incorporate multiple aspects naturally when customer selected multiple options

**Avoid these overused phrases:**
- 'Absolutely thrilled/delighted'
- 'Wonderful experience'
- 'Highly recommend'
- 'Exceeded expectations'
- 'Truly exceptional'
- 'Incredibly [adjective]'
- 'Without a doubt'
- 'Sang their praises'

**Customer answers:**
Experience aspects: $q1_text
Most impressive features: $q2_text
Product(perfume / incense Sticks) chosen: $q3_text
Service aspects: $q4_text
Recommendation level: $q5

**Write naturally like real people do:**
- Use contractions (I'm, it's, they're)
- Include minor imperfections in language
- Add personal context or reasoning
- Use varied sentence lengths
- Include specific details that show you were actually there
- Show, don't just tell emotions
- When multiple aspects are mentioned, weave them together naturally rather than listing them

Note: there is no lady staff in store mostly gens so in response dont mention like 'the lady explain me well ....'

Generate a unique review that sounds like a real customer with their own voice and personality.";

        $data = [
            "contents" => [
                ["parts" => [["text" => $prompt]]]
            ],
            "generationConfig" => [
                "temperature" => 0.9,
                "topK" => 1,
                "topP" => 1,
                "maxOutputTokens" => 200,
                "stopSequences" => []
            ]
        ];

        $ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite-preview-06-17:generateContent?key=' . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $errorMessage = "cURL Error: " . curl_error($ch);
        } else {
            $response = json_decode($result, true);

            if ($httpCode !== 200) {
                $errorMessage = "API Error (HTTP $httpCode): " . $result;
            } elseif (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
                $responseText = trim($response['candidates'][0]['content']['parts'][0]['text']);
            } elseif (isset($response['error'])) {
                $errorMessage = "API Error: " . $response['error']['message'];
            } else {
                $errorMessage = "Unexpected response format. Raw response: " . $result;
            }
        }

        curl_close($ch);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wanaromah Review Generator</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #000000 100%);
            min-height: 100vh;
            color: #fff;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated background particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background: rgba(193, 144, 101, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.3;
            }

            50% {
                transform: translateY(-100px) rotate(180deg);
                opacity: 0.8;
            }
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .main-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, #c19065, #d4a574, #c19065);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 30px rgba(193, 144, 101, 0.3);
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                filter: drop-shadow(0 0 20px rgba(193, 144, 101, 0.3));
            }

            to {
                filter: drop-shadow(0 0 30px rgba(193, 144, 101, 0.6));
            }
        }

        .subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 300;
            letter-spacing: 1px;
        }

        .progress-section {
            margin-bottom: 3rem;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .progress-text {
            font-size: 1.1rem;
            font-weight: 500;
            color: #c19065;
        }

        .step-indicator {
            display: flex;
            gap: 1rem;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.5s ease;
            position: relative;
            overflow: hidden;
            color: #fff;
        }

        .step.completed {
            background: linear-gradient(45deg, #c19065, #d4a574);
            color: #000;
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(193, 144, 101, 0.5);
        }

        .step.active {
            background: linear-gradient(45deg, #fff, #f0f0f0);
            color: #000;
            animation: pulse 1.5s ease-in-out infinite;
            box-shadow: 0 0 25px rgba(255, 255, 255, 0.4);
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .progress-bar {
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #c19065, #d4a574, #c19065);
            border-radius: 10px;
            width: 20%;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .question-container {
            flex: 1;
            position: relative;
            min-height: 500px;
            perspective: 1000px;
        }

        .question-card {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transform: translateX(100px) rotateY(15deg);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }

        .question-card.active {
            opacity: 1;
            transform: translateX(0) rotateY(0deg);
            pointer-events: auto;
        }

        .question-card.fade-out {
            opacity: 0;
            transform: translateX(-100px) rotateY(-15deg);
            pointer-events: none;
        }

        .question-number {
            font-size: 0.9rem;
            color: rgba(193, 144, 101, 0.8);
            font-weight: 500;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .question-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .question-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 2rem;
            font-style: italic;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .option-card {
            background: rgba(255, 255, 255, 0.03);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .option-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(193, 144, 101, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .option-card:hover::before {
            left: 100%;
        }

        .option-card:hover {
            border-color: rgba(193, 144, 101, 0.5);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(193, 144, 101, 0.2);
        }

        .option-card.selected {
            border-color: #c19065;
            background: rgba(193, 144, 101, 0.1);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(193, 144, 101, 0.3);
        }

        .option-card input {
            display: none;
        }

        .option-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .option-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .option-card.selected .option-icon {
            border-color: #c19065;
            background: #c19065;
        }

        .option-card.selected .option-icon::after {
            content: '✓';
            color: #000;
            font-weight: bold;
            font-size: 14px;
        }

        .option-text {
            font-size: 1.1rem;
            font-weight: 500;
            color: #fff;
            line-height: 1.4;
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            gap: 2rem;
            margin-top: 3rem;
        }

        .nav-btn {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            min-width: 150px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .nav-btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(45deg, #c19065, #d4a574);
            color: #000;
            box-shadow: 0 10px 30px rgba(193, 144, 101, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(193, 144, 101, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .btn-secondary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .error-notification {
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: linear-gradient(45deg, #000, #333);
            color: #c19065;
            padding: 1rem 2rem;
            border-radius: 15px;
            border: 2px solid #c19065;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            transform: translateX(400px);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            backdrop-filter: blur(20px);
        }

        .error-notification.show {
            transform: translateX(0);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(10px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            flex-direction: column;
        }

        .loading-animation {
            width: 100px;
            height: 100px;
            position: relative;
            margin-bottom: 2rem;
        }

        .loading-ring {
            width: 100%;
            height: 100%;
            border: 4px solid rgba(193, 144, 101, 0.2);
            border-top: 4px solid #c19065;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            font-size: 1.2rem;
            color: #c19065;
            font-weight: 500;
            text-align: center;
            animation: pulse-text 1.5s ease-in-out infinite;
        }

        @keyframes pulse-text {

            0%,
            100% {
                opacity: 0.7;
            }

            50% {
                opacity: 1;
            }
        }

        .result-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 3rem;
            border: 1px solid rgba(193, 144, 101, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            margin-top: 2rem;
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .result-card.show {
            opacity: 1;
            transform: translateY(0);
        }

        .result-header {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #c19065;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .review-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.03);
            padding: 2rem;
            border-radius: 15px;
            border-left: 4px solid #c19065;
            margin-bottom: 2rem;
            font-style: italic;
        }

        .copy-btn {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(45deg, #c19065, #d4a574);
            color: #000;
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .copy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(193, 144, 101, 0.3);
        }

        @media (max-width: 768px) {
            .main-title {
                font-size: 2.5rem;
            }

            .question-card {
                padding: 2rem;
            }

            .question-title {
                font-size: 1.8rem;
            }

            .options-grid {
                grid-template-columns: 1fr;
            }

            .navigation {
                flex-direction: column-reverse;
            }

            .error-notification {
                right: 1rem;
                left: 1rem;
                transform: translateY(-100px);
            }

            .error-notification.show {
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="particles" id="particles"></div>

    <div class="container">
        <div class="header">
            <h1 class="main-title">Wanaromah</h1>
            <p class="subtitle">Crafting Your Perfect Review Experience</p>
        </div>

        <div class="progress-section">
            <div class="progress-header">
                <div class="progress-text">Question <span id="currentStep">1</span> of 5</div>
                <div class="step-indicator">
                    <div class="step active" data-step="1">1</div>
                    <div class="step" data-step="2">2</div>
                    <div class="step" data-step="3">3</div>
                    <div class="step" data-step="4">4</div>
                    <div class="step" data-step="5">5</div>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </div>

        <form id="reviewForm" method="post" id="reviewForm">
            <div class="question-container">

                <!-- Question 1 -->
                <div class="question-card active" data-question="1">
                    <div class="question-number">Question 1 of 5</div>
                    <h2 class="question-title">How was your overall experience?</h2>
                    <p class="question-subtitle">Select all that resonated with your visit</p>

                    <div class="options-grid">
                        <label class="option-card">
                            <input type="checkbox" name="q1[]" value="Excellent – exceeded expectations" <?= in_array('Excellent – exceeded expectations', $_POST['q1'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Excellent – exceeded expectations</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q1[]" <?= in_array('Very Good – met expectations', $_POST['q1'] ?? []) ? 'checked' : '' ?> value="Very Good – met expectations">
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Very Good – met expectations</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q1[]" <?= in_array('Good – some minor issues', $_POST['q1'] ?? []) ? 'checked' : '' ?> value="Good – some minor issues">
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Good – some minor issues</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q1[]" <?= in_array('Fair – noticeable gaps', $_POST['q1'] ?? []) ? 'checked' : '' ?> value="Fair – noticeable gaps">
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Fair – noticeable gaps</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" <?= in_array('Poor – not satisfied', $_POST['q1'] ?? []) ? 'checked' : '' ?> name="q1[]" value="Poor – not satisfied">
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Poor – not satisfied</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Question 2 -->
                <div class="question-card" data-question="2">
                    <div class="question-number">Question 2 of 5</div>
                    <h2 class="question-title">What impressed you the most?</h2>
                    <p class="question-subtitle">Choose the aspects that stood out during your visit</p>

                    <div class="options-grid">
                        <label class="option-card">
                            <input type="checkbox" name="q2[]" <?= in_array('Fragrance quality & long-lasting scent', $_POST['q2'] ?? []) ? 'checked' : '' ?> value="Fragrance quality & long-lasting scent">
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Fragrance quality & long-lasting scent</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q2[]" <?= in_array('Personalized service & scent consultation', $_POST['q2'] ?? []) ? 'checked' : '' ?> value="Personalized service & scent consultation">
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Personalized service & scent consultation</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q2[]" value="Variety of scents and fragrance families" <?= in_array('Variety of scents and fragrance families', $_POST['q2'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Variety of scents and fragrance families</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q2[]" value="Price & value for money" <?= in_array('Price & value for money', $_POST['q2'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Price & value for money</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q2[]" value="Knowledgeable staff & ambiance" <?= in_array('Knowledgeable staff & ambiance', $_POST['q2'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Knowledgeable staff & ambiance</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Question 3 -->
                <div class="question-card" data-question="3">
                    <div class="question-number">Question 3 of 5</div>
                    <h2 class="question-title">Which products caught your attention?</h2>
                    <p class="question-subtitle">Select the categories you explored or purchased</p>

                    <div class="options-grid">
                        <label class="option-card">
                            <input type="checkbox" name="q3[]" value="Basic Customisation (fragrance families)" <?= in_array('Basic Customisation (fragrance families)', $_POST['q3'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Basic Customisation</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q3[]" value="LXE Customisation" <?= in_array('LXE Customisation', $_POST['q3'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">LXE Customisation</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q3[]" value="Divine Customisation" <?= in_array('Divine Customisation', $_POST['q3'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Divine Customisation</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q3[]" value="Signature Blends" <?= in_array('Signature Blends', $_POST['q3'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Signature Blends</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q3[]" value="Vintage Miniatures" <?= in_array('Vintage Miniatures', $_POST['q3'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Vintage Miniatures</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q3[]" value="Incense Sticks" <?= in_array('Incense Sticks', $_POST['q3'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Incense Sticks</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Question 4 -->
                <div class="question-card" data-question="4">
                    <div class="question-number">Question 4 of 5</div>
                    <h2 class="question-title">How was our customer service?</h2>
                    <p class="question-subtitle">Share your experience with our team</p>

                    <div class="options-grid">
                        <label class="option-card">
                            <input type="checkbox" name="q4[]" value="Friendly, helpful & professional <?= in_array('Friendly, helpful & professional', $_POST['q4'] ?? []) ? 'checked' : '' ?>">
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Friendly, helpful & professional</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q4[]" value="Courteous, patient & informative" <?= in_array('Courteous, patient & informative', $_POST['q4'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Courteous, patient & informative</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q4[]" value="Average – okay but not memorable" <?= in_array('Average – okay but not memorable', $_POST['q4'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Average – okay but not memorable</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q4[]" value="Inconsistent across visits" <?= in_array('Inconsistent across visits', $_POST['q4'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Inconsistent across visits</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="checkbox" name="q4[]" value="Unsatisfactory – needs improvement" <?= in_array('Unsatisfactory – needs improvement', $_POST['q4'] ?? []) ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Unsatisfactory – needs improvement</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Question 5 -->
                <div class="question-card" data-question="5">
                    <div class="question-number">Question 5 of 5</div>
                    <h2 class="question-title">Would you recommend us?</h2>
                    <p class="question-subtitle">Help us understand your likelihood to recommend Wanaromah</p>

                    <div class="options-grid">
                        <label class="option-card">
                            <input type="radio" name="q5" value="Absolutely – already have!" <?= ($_POST['q5'] ?? '') === 'Absolutely – already have!' ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Absolutely – already have!</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="radio" name="q5" value="Yes – definitely" <?= ($_POST['q5'] ?? '') === 'Yes – definitely' ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Yes – definitely</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="radio" name="q5" value="Maybe – depends" <?= ($_POST['q5'] ?? '') === 'Maybe – depends' ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Maybe – depends</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="radio" name="q5" value="Not likely" <?= ($_POST['q5'] ?? '') === 'Not likely' ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">Not likely</div>
                            </div>
                        </label>

                        <label class="option-card">
                            <input type="radio" name="q5" value="No" <?= ($_POST['q5'] ?? '') === 'No' ? 'checked' : '' ?>>
                            <div class="option-content">
                                <div class="option-icon"></div>
                                <div class="option-text">No</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="navigation">
                <button type="button" class="nav-btn btn-secondary" id="prevBtn" onclick="previousQuestion()" disabled>
                    Previous
                </button>
                <button type="button" class="nav-btn btn-primary" id="nextBtn" onclick="nextQuestion()">
                    Next
                </button>
            </div>
        </form>

        <div class="result-card" id="resultCard">
            <h3 class="result-header">✨ Your Personalized Review</h3>
            <div class="review-text" id="reviewText"></div>
            <button type="button" class="copy-btn" onclick="copyReview()">
                Copy & Post Review
            </button>
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-animation">
            <div class="loading-ring"></div>
        </div>
        <div class="loading-text">Crafting your perfect review...</div>
    </div>

    <div class="error-notification" id="errorNotification">
        <strong>⚠️ Please select at least one option to continue</strong>
    </div>

    <script>
        let currentQuestion = 1;
        const totalQuestions = 5;

        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 15;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';

                // Random size and position
                const size = Math.random() * 6 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 4) + 's';

                particlesContainer.appendChild(particle);
            }
        }

        // Handle option selection
        function handleOptionSelection() {
            const options = document.querySelectorAll('.option-card');

            options.forEach(option => {
                option.addEventListener('click', function() {
                    const input = this.querySelector('input');

                    if (input.type === 'radio') {
                        // Radio buttons - clear other selections in same group
                        const groupName = input.name;
                        document.querySelectorAll(`input[name="${groupName}"]`).forEach(radio => {
                            radio.closest('.option-card').classList.remove('selected');
                        });

                        input.checked = true;
                        this.classList.add('selected');
                    } else {
                        // Checkboxes - toggle selection
                        input.checked = !input.checked;
                        this.classList.toggle('selected', input.checked);
                    }
                });
            });
        }

        function updateProgress() {
            const progress = (currentQuestion / totalQuestions) * 100;
            const progressFill = document.getElementById('progressFill');
            const currentStepSpan = document.getElementById('currentStep');

            progressFill.style.width = progress + '%';
            currentStepSpan.textContent = currentQuestion;

            // Update step indicators
            document.querySelectorAll('.step').forEach((step, index) => {
                const stepNumber = index + 1;
                step.classList.remove('active', 'completed');

                if (stepNumber < currentQuestion) {
                    step.classList.add('completed');
                } else if (stepNumber === currentQuestion) {
                    step.classList.add('active');
                }
            });
        }

        function validateCurrentQuestion() {
            const currentQuestionCard = document.querySelector(`[data-question="${currentQuestion}"]`);

            if (currentQuestion === 5) {
                // Radio button validation for question 5
                const radioInputs = currentQuestionCard.querySelectorAll('input[type="radio"]');
                return Array.from(radioInputs).some(input => input.checked);
            } else {
                // Checkbox validation for questions 1-4
                const checkboxInputs = currentQuestionCard.querySelectorAll('input[type="checkbox"]');
                return Array.from(checkboxInputs).some(input => input.checked);
            }
        }

        function showError() {
            const errorNotification = document.getElementById('errorNotification');
            errorNotification.classList.add('show');

            // Auto-hide after 3 seconds
            setTimeout(() => {
                errorNotification.classList.remove('show');
            }, 3000);
        }

        function nextQuestion() {
            if (!validateCurrentQuestion()) {
                showError();
                return;
            }

            if (currentQuestion < totalQuestions) {
                // Transition to next question
                const currentCard = document.querySelector(`[data-question="${currentQuestion}"]`);
                currentCard.classList.add('fade-out');

                setTimeout(() => {
                    currentCard.classList.remove('active', 'fade-out');
                    currentQuestion++;

                    const nextCard = document.querySelector(`[data-question="${currentQuestion}"]`);
                    nextCard.classList.add('active');

                    updateProgress();
                    updateButtons();
                }, 400);
            } else if (currentQuestion === totalQuestions) {
                // Generate review
                generateReview();
            }
        }

        function previousQuestion() {
            if (currentQuestion > 1) {
                const currentCard = document.querySelector(`[data-question="${currentQuestion}"]`);
                currentCard.classList.add('fade-out');

                setTimeout(() => {
                    currentCard.classList.remove('active', 'fade-out');
                    currentQuestion--;

                    const prevCard = document.querySelector(`[data-question="${currentQuestion}"]`);
                    prevCard.classList.add('active');

                    updateProgress();
                    updateButtons();
                }, 400);
            }
        }

        function updateButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            prevBtn.disabled = currentQuestion === 1;

            if (currentQuestion === totalQuestions) {
                nextBtn.innerHTML = '✨ Generate Review';
                nextBtn.attributes['type'].value = 'submit';
            } else {
                nextBtn.innerHTML = 'Next';
            }
        }

        function generateReview() {
            // Show loading overlay
            document.getElementById('loadingOverlay').style.display = 'flex';

            // Collect form data
            const formData = new FormData();

            // Collect checkbox answers
            for (let i = 1; i <= 4; i++) {
                const checkboxes = document.querySelectorAll(`input[name="q${i}[]"]:checked`);
                checkboxes.forEach(checkbox => {
                    formData.append(`q${i}[]`, checkbox.value);
                });
            }

            // Collect radio answer
            const radioInput = document.querySelector('input[name="q5"]:checked');
            if (radioInput) {
                formData.append('q5', radioInput.value);
            }

            // Simulate review generation with more sophisticated mock reviews
            const mockReviews = [
                "Wandered into Wanaromah on a whim last Saturday and honestly wasn't prepared for how thorough they'd be. The staff member spent nearly 20 minutes understanding what I actually wanted instead of just showing me the most expensive options. Ended up with something from their signature collection that I've been getting compliments on all week. The whole experience felt more like a consultation than a sales pitch.",

                "My sister insisted I check out this place for a birthday gift, and I'm glad I listened. The guy working there had this methodical approach - asked about the recipient's personality, usual preferences, even what they wear to work. We settled on one of their custom blends and when I gave it to her, she said it was exactly something she would have chosen herself. That's skill right there.",

                "Been using the same cologne for years until a friend dragged me here. The customization process was pretty interesting - they let me smell different base notes and explained how they'd interact with my skin chemistry. Three weeks later and I'm still surprised by how long this stuff lasts. Not cheap, but you definitely get what you pay for.",

                "Stopped by during lunch break expecting a quick purchase, but ended up staying an hour. The staff member was patient enough to let me test multiple options and didn't pressure me when I said I needed to think about it. Came back the next day and got their LXE customization. Worth the extra time and money for something that actually suits me.",

                "Friend recommended this place after I complained about never finding fragrances that work with my skin. The consultation was detailed - they asked about my daily routine, what scents I gravitate toward, even what my job environment is like. The final result was spot on. It's been two months and people still ask what I'm wearing."
            ];

            // Simulate API processing time
            setTimeout(() => {
                const randomReview = mockReviews[Math.floor(Math.random() * mockReviews.length)];

                // Hide loading and show result
                document.getElementById('loadingOverlay').style.display = 'none';
                document.getElementById('reviewText').innerHTML = `<?php if ($errorMessage): ?>
            <div class="error">
                <strong>Error:</strong><br>
                <?= nl2br(htmlspecialchars($errorMessage)) ?>
            </div>
        <?php endif; ?>

        <?php if ($responseText): ?>
            <div class="response">
                <p style="font-weight:border;">Review:</p>
                <div class="reviewText"><?= nl2br(htmlspecialchars($responseText)) ?></div>
                <button onclick="copyReview()">Copy Review</button>
            </div>
        <?php endif; ?>`
                document.getElementById('resultCard').classList.add('show');

                // Hide form
                document.getElementById('reviewForm').style.display = 'none';

                // Add some celebration particles
                createCelebrationEffect();

            }, 3000); // 3 second delay for dramatic effect
        }

        function createCelebrationEffect() {
            // Create temporary celebration particles
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.style.position = 'fixed';
                particle.style.left = '50%';
                particle.style.top = '20%';
                particle.style.width = '8px';
                particle.style.height = '8px';
                particle.style.background = '#c19065';
                particle.style.borderRadius = '50%';
                particle.style.pointerEvents = 'none';
                particle.style.zIndex = '9999';

                document.body.appendChild(particle);

                // Animate particle
                const angle = (i / 20) * Math.PI * 2;
                const velocity = 100 + Math.random() * 50;
                const tx = Math.cos(angle) * velocity;
                const ty = Math.sin(angle) * velocity;

                particle.animate([{
                        transform: 'translate(-50%, -50%) scale(0)',
                        opacity: 1
                    },
                    {
                        transform: `translate(calc(-50% + ${tx}px), calc(-50% + ${ty}px)) scale(1)`,
                        opacity: 0.8
                    },
                    {
                        transform: `translate(calc(-50% + ${tx * 2}px), calc(-50% + ${ty * 2}px)) scale(0)`,
                        opacity: 0
                    }
                ], {
                    duration: 2000,
                    easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
                }).onfinish = () => particle.remove();
            }
        }

        function copyReview() {
            const reviewText = document.getElementById('reviewText').textContent;

            navigator.clipboard.writeText(reviewText)
                .then(() => {
                    // Change button text temporarily
                    const copyBtn = event.target;
                    const originalText = copyBtn.innerHTML;
                    copyBtn.innerHTML = '✅ Copied!';
                    copyBtn.style.background = 'linear-gradient(45deg, #fff, #f0f0f0)';
                    copyBtn.style.color = '#000';

                    setTimeout(() => {
                        copyBtn.innerHTML = originalText;
                        copyBtn.style.background = 'linear-gradient(45deg, #c19065, #d4a574)';
                        copyBtn.style.color = '#000';

                        // Open Google Reviews
                        window.open("https://search.google.com/local/writereview?placeid=ChIJw4Y0RiVnUjoR89-2ZrZgM2o", "_blank");
                    }, 1500);
                })
                .catch(err => {
                    console.error("Clipboard copy failed:", err);
                    alert("Unable to copy text. Please copy manually.");
                });
        }

        // Initialize everything
        function init() {
            createParticles();
            handleOptionSelection();
            updateProgress();
            updateButtons();

            // Add keyboard navigation
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' && !event.shiftKey) {
                    event.preventDefault();
                    nextQuestion();
                } else if (event.key === 'ArrowLeft' && event.ctrlKey) {
                    event.preventDefault();
                    previousQuestion();
                } else if (event.key === 'ArrowRight' && event.ctrlKey) {
                    event.preventDefault();
                    nextQuestion();
                }
            });

            // Add smooth scrolling behavior
            window.addEventListener('resize', function() {
                // Ensure proper scaling on mobile
                const container = document.querySelector('.container');
                container.style.minHeight = window.innerHeight + 'px';
            });
        }

        // Start the magic
        init();
    </script>
</body>

</html>