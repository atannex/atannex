<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="Share your Atannex story - Submit your testimonial and be part of our community voice">
    <meta name="theme-color" content="#0f172a">
    <title>Submit Your Testimonial | Atannex News Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Outfit', sans-serif;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {
            from {
                left: -100%;
            }

            to {
                left: 100%;
            }
        }

        @keyframes glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.5), 0 0 40px rgba(6, 182, 212, 0.3);
            }

            50% {
                box-shadow: 0 0 30px rgba(59, 130, 246, 0.7), 0 0 60px rgba(6, 182, 212, 0.5);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes pulse-scale {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes cardFlip {
            0% {
                transform: rotateY(0deg) rotateX(0deg);
                opacity: 0;
            }

            100% {
                transform: rotateY(0deg) rotateX(0deg);
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-60px) rotateY(20deg);
            }

            to {
                opacity: 1;
                transform: translateX(0) rotateY(0deg);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(60px) rotateY(-20deg);
            }

            to {
                opacity: 1;
                transform: translateX(0) rotateY(0deg);
            }
        }

        @keyframes bounce-in {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(30px);
            }

            60% {
                opacity: 1;
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        @keyframes star-pop {
            0% {
                transform: scale(0.5) rotate(-180deg);
                opacity: 0;
            }

            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        @keyframes badge-pulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }
        }

        @keyframes gradient-flow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes avatar-spin {
            0% {
                transform: scale(0.5) rotate(0deg);
            }

            100% {
                transform: scale(1) rotate(360deg);
            }
        }

        .fade-up {
            animation: fadeUp 0.6s ease-out forwards;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .glass-effect {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(148, 163, 184, 0.15);
        }

        .premium-gradient-border {
            position: relative;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(20, 30, 48, 0.85) 100%);
            border: 1px solid transparent;
            background-clip: padding-box;
            border-image: linear-gradient(135deg, rgba(59, 130, 246, 0.5), rgba(6, 182, 212, 0.3)) 1;
            overflow: hidden;
        }

        .premium-form-card {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(20, 30, 48, 0.9) 100%);
            border: 1px solid rgba(59, 130, 246, 0.35);
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .premium-form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        .premium-form-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            filter: blur(40px);
            pointer-events: none;
        }

        .badge-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.25), rgba(6, 182, 212, 0.2));
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.4);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
            animation: slideIn 0.6s ease-out;
        }

        .section-title {
            font-size: clamp(2.25rem, 5.5vw, 4rem);
            font-weight: 900;
            letter-spacing: -0.02em;
            color: white;
            line-height: 1.15;
        }

        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 50%, #0ea5e9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            justify-content: center;
            padding: 1rem 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .btn-gradient:hover::before {
            transform: translateX(100%);
        }

        .btn-gradient:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 50px rgba(59, 130, 246, 0.5);
        }

        .btn-gradient:active {
            transform: translateY(-1px);
        }

        .btn-outline {
            background: rgba(59, 130, 246, 0.08);
            color: #3b82f6;
            font-weight: 600;
            border: 2px solid rgba(59, 130, 246, 0.3);
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
            padding: 0.875rem 2rem;
        }

        .btn-outline:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.6);
            transform: translateY(-3px);
        }

        .form-input,
        .form-textarea,
        .form-select {
            background: rgba(15, 23, 42, 0.9);
            border: 1.5px solid rgba(71, 85, 105, 0.4);
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            color: white;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-family: 'Outfit', sans-serif;
            width: 100%;
            font-size: 0.95rem;
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: rgba(148, 163, 184, 0.5);
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            background: rgba(15, 23, 42, 0.98);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2), 0 0 30px rgba(59, 130, 246, 0.3);
            transform: translateY(-2px);
        }

        .form-label {
            font-weight: 600;
            color: white;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            display: block;
            text-transform: capitalize;
            letter-spacing: 0.3px;
        }

        .star {
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            color: rgba(148, 163, 184, 0.4);
            font-size: 1.75rem;
            filter: drop-shadow(0 0 0 rgba(251, 191, 36, 0));
        }

        .star:hover,
        .star.active {
            color: #fbbf24;
            text-shadow: 0 0 15px rgba(251, 191, 36, 0.6);
            transform: scale(1.3) rotate(15deg);
            filter: drop-shadow(0 0 8px rgba(251, 191, 36, 0.4));
        }

        .tab-button {
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            padding: 1.25rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            color: rgba(148, 163, 184, 0.7);
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .tab-button::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #06b6d4);
            transition: width 0.3s ease;
        }

        .tab-button.active {
            color: white;
        }

        .tab-button.active::after {
            width: 100%;
        }

        .tab-button:hover:not(.active) {
            color: rgba(226, 232, 240, 0.9);
        }

        .tab-content {
            display: none;
            animation: fadeUp 0.4s ease-out;
        }

        .tab-content.active {
            display: block;
        }

        .success-message,
        .error-message {
            padding: 1.5rem;
            border-radius: 0.875rem;
            display: none;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            backdrop-filter: blur(10px);
        }

        .success-message {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.4);
            border-left-color: #22c55e;
            color: #86efac;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            border-left-color: #ef4444;
            color: #fca5a5;
        }

        .success-message.show,
        .error-message.show {
            display: block;
            animation: slideIn 0.4s ease-out;
        }

        .benefit-item {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            padding: 1.75rem;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(6, 182, 212, 0.05) 100%);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .benefit-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.4), transparent);
        }

        .benefit-item:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(6, 182, 212, 0.1) 100%);
            border-color: rgba(59, 130, 246, 0.4);
            transform: translateX(8px);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.15);
        }

        .benefit-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.25), rgba(6, 182, 212, 0.15));
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .benefit-item:hover .benefit-icon {
            transform: scale(1.15) rotate(-5deg);
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.35), rgba(6, 182, 212, 0.25));
        }

        .photo-drop-zone {
            position: relative;
            padding: 2.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px dashed rgba(71, 85, 105, 0.5);
            border-radius: 1rem;
            background: rgba(59, 130, 246, 0.05);
            cursor: pointer;
        }

        .photo-drop-zone:hover {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.1);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
        }

        .progress-indicator {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .progress-dot {
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            background: rgba(71, 85, 105, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .progress-dot.active {
            background: linear-gradient(135deg, #3b82f6, #06b6d4);
            transform: scale(1.3);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
        }

        .progress-dot.completed {
            background: #22c55e;
        }

        .field-hint {
            font-size: 0.85rem;
            color: rgba(148, 163, 184, 0.7);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .field-hint i {
            font-size: 0.75rem;
        }

        .info-box {
            padding: 1.25rem;
            border-radius: 0.875rem;
            border-left: 4px solid;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .info-box-blue {
            background: rgba(59, 130, 246, 0.15);
            border-left-color: #3b82f6;
            color: #93c5fd;
        }

        .info-box-green {
            background: rgba(34, 197, 94, 0.15);
            border-left-color: #22c55e;
            color: #86efac;
        }

        .info-box i {
            font-size: 1.25rem;
            flex-shrink: 0;
            margin-top: 0.2rem;
        }

        .stat-card {
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(6, 182, 212, 0.05) 100%);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: #3b82f6;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(6, 182, 212, 0.1) 100%);
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #3b82f6, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(148, 163, 184, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 1024px) {
            .section-title {
                font-size: clamp(1.75rem, 4.5vw, 3rem);
            }

            .benefit-item {
                gap: 1rem;
                padding: 1.5rem;
            }

            .benefit-icon {
                width: 3rem;
                height: 3rem;
                font-size: 1.5rem;
            }

            .tab-button {
                padding: 1rem 1.25rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: clamp(1.5rem, 3.8vw, 2.25rem);
            }

            .benefit-item {
                gap: 0.875rem;
                padding: 1.25rem;
            }

            .benefit-icon {
                width: 2.75rem;
                height: 2.75rem;
                font-size: 1.25rem;
            }

            .tab-button {
                padding: 0.75rem 1rem;
                font-size: 0.8rem;
            }

            .form-input,
            .form-textarea,
            .form-select {
                padding: 0.875rem 1rem;
                font-size: 0.9rem;
            }

            .form-label {
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }

            .photo-drop-zone {
                padding: 1.5rem;
            }
        }

        .testimonial-card {
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid rgba(71, 85, 105, 0.3);
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(20, 30, 48, 0.7) 100%);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
            perspective: 1200px;
            transform-style: preserve-3d;
        }

        .testimonial-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.3), transparent);
        }

        .testimonial-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 0%, rgba(59, 130, 246, 0.1), transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }

        .testimonial-card:hover::after {
            opacity: 1;
        }

        .testimonial-card:hover {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.85) 100%);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 60px rgba(59, 130, 246, 0.25), 0 0 50px rgba(6, 182, 212, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .testimonial-card.hidden {
            display: none;
            animation: none;
        }

        .testimonial-card.visible {
            display: flex;
            animation: bounce-in 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .testimonial-card.visible:nth-child(1) {
            animation-delay: 0.05s;
        }

        .testimonial-card.visible:nth-child(2) {
            animation-delay: 0.15s;
        }

        .testimonial-card.visible:nth-child(3) {
            animation-delay: 0.25s;
        }

        .testimonial-card.visible:nth-child(4) {
            animation-delay: 0.35s;
        }

        .testimonial-card.visible:nth-child(5) {
            animation-delay: 0.45s;
        }

        .testimonial-card.visible:nth-child(6) {
            animation-delay: 0.55s;
        }

        .testimonial-card.visible:nth-child(7) {
            animation-delay: 0.65s;
        }

        .testimonial-card.visible:nth-child(8) {
            animation-delay: 0.75s;
        }

        .testimonial-card.visible:nth-child(9) {
            animation-delay: 0.85s;
        }

        .testimonial-card:hover .star-rating-icon {
            animation: star-pop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .testimonial-card:hover .badge-primary {
            animation: badge-pulse 0.6s ease;
        }

        .testimonial-card:hover .user-avatar {
            animation: avatar-spin 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .star-rating-icon {
            display: inline-flex;
            gap: 0.25rem;
            transition: all 0.3s ease;
        }

        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            border: 2px solid rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            border-color: rgba(59, 130, 246, 0.8);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }

        .filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.125rem;
            border-radius: 9999px;
            border: 1.5px solid rgba(71, 85, 105, 0.4);
            background: transparent;
            color: rgba(148, 163, 184, 0.8);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: capitalize;
            letter-spacing: 0.3px;
        }

        .filter-btn:hover {
            border-color: rgba(59, 130, 246, 0.6);
            color: rgba(226, 232, 240, 0.9);
            background: rgba(59, 130, 246, 0.1);
        }

        .filter-btn.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.3), rgba(6, 182, 212, 0.2));
            border-color: rgba(59, 130, 246, 0.6);
            color: white;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }

        .filter-btn i {
            font-size: 0.95rem;
        }

        @media (max-width: 640px) {
            .section-title {
                font-size: clamp(1.25rem, 3.2vw, 1.875rem);
            }

            .badge-primary {
                padding: 0.6rem 1.125rem;
                font-size: 0.7rem;
            }

            .btn-gradient,
            .btn-outline {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
                width: 100%;
            }

            .tab-button {
                padding: 0.625rem 0.75rem;
                font-size: 0.75rem;
            }

            .form-input,
            .form-textarea,
            .form-select {
                padding: 0.75rem 1rem;
                font-size: 15px;
            }

            .form-label {
                font-size: 0.85rem;
            }

            .star {
                font-size: 1.5rem;
            }

            textarea {
                min-height: 140px;
                font-size: 15px !important;
            }

            .benefit-item {
                padding: 1rem;
            }

            .benefit-icon {
                width: 2.5rem;
                height: 2.5rem;
                font-size: 1.125rem;
            }

            .stat-card {
                padding: 1.25rem;
            }

            .stat-number {
                font-size: 2rem;
            }
        }

    </style>
</head>
<body class="w-full overflow-x-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">

    <!-- NAVBAR -->
    <nav class="fixed top-0 z-50 w-full border-b glass-effect border-slate-700/20">
        <div class="flex items-center justify-between px-4 py-3 mx-auto max-w-7xl sm:px-6 sm:py-4 lg:px-8">
            <a href="/" class="flex items-center gap-2 transition hover:opacity-90">
                <div class="flex items-center justify-center text-sm font-bold text-white rounded-lg w-9 sm:w-10 h-9 sm:h-10 bg-gradient-to-br from-blue-500 to-cyan-500 sm:text-lg">A</div>
                <span class="hidden text-lg font-bold text-white sm:inline sm:text-xl">Atannex</span>
            </a>
            <div class="items-center hidden gap-8 lg:flex">
                <a href="/#about" class="text-sm font-medium transition text-slate-300 hover:text-white">About</a>
                <a href="/#stories" class="text-sm font-medium transition text-slate-300 hover:text-white">Stories</a>
                <a href="/testimonials" class="text-sm font-medium text-blue-400 transition hover:text-cyan-300">Testimonials</a>
                <a href="/#contact" class="text-sm font-medium transition text-slate-300 hover:text-white">Contact</a>
            </div>
            <a href="/#subscribe" class="hidden px-6 py-2 text-sm btn-gradient lg:flex">
                <i class="fas fa-envelope"></i>
                <span class="hidden sm:inline">Subscribe</span>
            </a>
        </div>
    </nav>

    <!-- PREMIUM HERO SECTION -->
    <section class="px-4 pt-20 pb-16 sm:pt-28 sm:pb-24 sm:px-6 md:px-8 lg:pt-32 lg:pb-28">
        <div class="max-w-6xl mx-auto">
            <div class="grid items-center gap-8 lg:grid-cols-2 sm:gap-12 lg:gap-16">
                <div class="space-y-6 sm:space-y-8">
                    <div class="fade-up">
                        <div class="badge-primary w-fit">
                            <i class="text-red-400 fas fa-heart"></i>
                            <span>Share Your Impact</span>
                        </div>
                    </div>

                    <h1 class="delay-100 section-title fade-up">
                        <span>Amplify</span>
                        <br>
                        <span class="gradient-text">Your Voice</span>
                        <br>
                        <span>Globally</span>
                    </h1>

                    <p class="max-w-lg text-base leading-relaxed delay-200 sm:text-lg text-slate-300 fade-up">
                        Whether you're a community leader, entrepreneur, educator, or diaspora member — your story shapes the narrative. Share how Atannex has impacted you and inspire thousands worldwide.
                    </p>

                    <div class="flex items-center gap-3 pt-2 delay-300 sm:gap-4 sm:pt-4 fade-up">
                        <div class="flex -space-x-2.5 sm:-space-x-3">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=40&h=40&fit=crop" alt="" class="w-8 h-8 border-2 rounded-full sm:w-10 sm:h-10 border-slate-900">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop" alt="" class="w-8 h-8 border-2 rounded-full sm:w-10 sm:h-10 border-slate-900">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=40&h=40&fit=crop" alt="" class="w-8 h-8 border-2 rounded-full sm:w-10 sm:h-10 border-slate-900">
                        </div>
                        <p class="text-xs sm:text-sm text-slate-400">Join 450+ voices from 50+ countries</p>
                    </div>
                </div>

                <div class="justify-center hidden delay-300 lg:flex fade-up">
                    <div class="relative w-full max-w-md">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl sm:rounded-3xl blur-3xl opacity-20"></div>
                        <div class="relative p-6 space-y-4 border sm:p-8 sm:space-y-6 rounded-2xl sm:rounded-3xl bg-slate-900/60 border-blue-500/30">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=80&h=80&fit=crop" alt="User" class="flex-shrink-0 object-cover w-12 h-12 rounded-full sm:w-16 sm:h-16">
                                <div class="flex-grow min-w-0">
                                    <div class="flex gap-0.5 sm:gap-1 mb-2">
                                        <i class="text-xs text-yellow-400 fas fa-star sm:text-sm"></i>
                                        <i class="text-xs text-yellow-400 fas fa-star sm:text-sm"></i>
                                        <i class="text-xs text-yellow-400 fas fa-star sm:text-sm"></i>
                                        <i class="text-xs text-yellow-400 fas fa-star sm:text-sm"></i>
                                        <i class="text-xs text-yellow-400 fas fa-star sm:text-sm"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-white sm:text-base">Amazing Experience</p>
                                    <p class="text-xs leading-relaxed text-slate-400">"Atannex amplified our community's voice"</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 pt-4 border-t sm:gap-4 sm:pt-6 border-slate-700/30">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop" alt="User" class="flex-shrink-0 object-cover w-12 h-12 rounded-full sm:w-16 sm:h-16">
                                <div class="flex-grow min-w-0">
                                    <div class="flex gap-0.5 sm:gap-1 mb-2">
                                        <i class="text-xs text-yellow-400 fas fa-star sm:text-sm"></i>
                                        <i class="text-xs text-yellow-400 fas fa-star sm:text-sm"></i>
                                        <i class="text-xs text-yellow-400 fas fa-star sm:text-sm"></i>
                                        <i class="text-xs text-yellow-400 fas fa-star sm:text-sm"></i>
                                        <i class="text-xs text-yellow-400 fas fa-star sm:text-sm"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-white sm:text-base">Life Changing</p>
                                    <p class="text-xs leading-relaxed text-slate-400">"Doors opened through coverage"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PREMIUM FORM SECTION -->
    <section class="px-4 py-16 sm:py-24 md:py-32 sm:px-6 md:px-8 bg-gradient-to-b from-slate-900/30 to-slate-950/50">
        <div class="mx-auto max-w-7xl">
            <div class="grid items-start gap-8 lg:grid-cols-5 lg:gap-12 xl:gap-16">
                <!-- LEFT SIDEBAR - BENEFITS -->
                <div class="order-2 space-y-6 sm:space-y-8 lg:col-span-2 lg:order-1">
                    <div>
                        <h2 class="mb-4 text-2xl font-bold text-white sm:text-3xl md:text-4xl">Why Share Your Story?</h2>
                        <p class="text-sm leading-relaxed sm:text-base lg:text-lg text-slate-300">
                            Your testimonial inspires action and creates meaningful impact. Every voice strengthens our community.
                        </p>
                    </div>

                    <div class="space-y-4 sm:space-y-5">
                        <div class="benefit-item">
                            <div class="text-blue-300 benefit-icon" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.3), rgba(6, 182, 212, 0.1));">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div>
                                <h4 class="mb-2 font-semibold text-white">Amplify Your Message</h4>
                                <p class="text-xs sm:text-sm text-slate-400">Reach 50+ countries with your impactful story</p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon text-cyan-300" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.3), rgba(14, 165, 233, 0.1));">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div>
                                <h4 class="mb-2 font-semibold text-white">Build Bridges</h4>
                                <p class="text-xs sm:text-sm text-slate-400">Connect diaspora with community initiatives</p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="text-green-300 benefit-icon" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.3), rgba(22, 163, 74, 0.1));">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div>
                                <h4 class="mb-2 font-semibold text-white">Drive Real Impact</h4>
                                <p class="text-xs sm:text-sm text-slate-400">Inspire tangible change in Lebialem</p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="text-purple-300 benefit-icon" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.3), rgba(139, 92, 246, 0.1));">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h4 class="mb-2 font-semibold text-white">Secure & Private</h4>
                                <p class="text-xs sm:text-sm text-slate-400">Your data protected with enterprise security</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT FORM -->
                <div class="order-1 lg:col-span-3 lg:order-2">
                    <div class="space-y-6">
                        <!-- PROGRESS INDICATOR -->
                        <div class="progress-indicator">
                            <div class="progress-dot active" data-tab="1"></div>
                            <div class="progress-dot" data-tab="2"></div>
                            <div class="progress-dot" data-tab="3"></div>
                            <div class="progress-dot" data-tab="4"></div>
                        </div>

                        <!-- PREMIUM FORM CARD -->
                        <div class="sticky overflow-hidden premium-form-card rounded-2xl sm:rounded-3xl top-16 sm:top-20 lg:top-24">
                            <!-- TAB NAVIGATION -->
                            <div class="flex overflow-x-auto border-b border-slate-700/30 bg-slate-800/30">
                                <button class="flex-1 text-white tab-button active" data-tab="tab-1">
                                    <i class="fas fa-user"></i>
                                    <span class="hidden sm:inline">Personal</span>
                                </button>
                                <button class="flex-1 tab-button" data-tab="tab-2">
                                    <i class="fas fa-star"></i>
                                    <span class="hidden sm:inline">Experience</span>
                                </button>
                                <button class="flex-1 tab-button" data-tab="tab-3">
                                    <i class="fas fa-pen"></i>
                                    <span class="hidden sm:inline">Story</span>
                                </button>
                                <button class="flex-1 tab-button" data-tab="tab-4">
                                    <i class="fas fa-check"></i>
                                    <span class="hidden sm:inline">Submit</span>
                                </button>
                            </div>

                            <!-- FORM CONTENT -->
                            <form id="testimonialForm" class="relative z-10 p-6 sm:p-8 lg:p-10">
                                <!-- SUCCESS MESSAGE -->
                                <div class="success-message" id="successMessage">
                                    <div class="flex items-start gap-2 sm:gap-3">
                                        <i class="flex-shrink-0 text-xl sm:text-2xl fas fa-check-circle"></i>
                                        <div>
                                            <p class="text-sm font-semibold sm:text-base">Thank You!</p>
                                            <p class="mt-1 text-xs sm:text-sm">Your testimonial has been submitted successfully. We'll review it within 5-7 business days.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- ERROR MESSAGE -->
                                <div class="error-message" id="errorMessage">
                                    <div class="flex items-start gap-2 sm:gap-3">
                                        <i class="flex-shrink-0 text-xl sm:text-2xl fas fa-exclamation-circle"></i>
                                        <div>
                                            <p class="text-sm font-semibold sm:text-base">Error</p>
                                            <p class="mt-1 text-xs sm:text-sm" id="errorText">Please fill out all required fields.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 1: PERSONAL INFORMATION -->
                                <div class="tab-content active" id="tab-1">
                                    <div class="space-y-4 sm:space-y-5">
                                        <div>
                                            <label class="form-label">Full Name <span class="text-red-400">*</span></label>
                                            <input type="text" id="fullName" placeholder="Enter your full name" class="form-input" required>
                                            <p class="field-hint"><i class="fas fa-info-circle"></i>How should we credit your story?</p>
                                        </div>

                                        <div>
                                            <label class="form-label">Email Address <span class="text-red-400">*</span></label>
                                            <input type="email" id="email" placeholder="your@email.com" class="form-input" required>
                                            <p class="field-hint"><i class="fas fa-lock"></i>Only used for confirmation and updates</p>
                                        </div>

                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
                                            <div>
                                                <label class="form-label">Location <span class="text-red-400">*</span></label>
                                                <input type="text" id="location" placeholder="City/Country" class="form-input" required>
                                            </div>
                                            <div>
                                                <label class="form-label">Your Role <span class="text-red-400">*</span></label>
                                                <select id="role" class="form-select" required>
                                                    <option value="">Select your role</option>
                                                    <option value="leader">Community Leader</option>
                                                    <option value="educator">Educator</option>
                                                    <option value="entrepreneur">Entrepreneur</option>
                                                    <option value="health">Healthcare Professional</option>
                                                    <option value="farmer">Farmer</option>
                                                    <option value="diaspora">Diaspora Member</option>
                                                    <option value="journalist">Journalist</option>
                                                    <option value="student">Student</option>
                                                    <option value="ngo">NGO/Non-profit</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="form-label">Organization (Optional)</label>
                                            <input type="text" id="organization" placeholder="Your organization or institution" class="form-input">
                                        </div>

                                        <div class="flex justify-end gap-2 pt-4 border-t sm:gap-3 sm:pt-6 border-slate-700/30">
                                            <button type="button" class="tab-next btn-gradient">
                                                Next <i class="ml-1 sm:ml-2 fas fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 2: EXPERIENCE RATINGS -->
                                <div class="hidden tab-content" id="tab-2">
                                    <div class="space-y-6 sm:space-y-7">
                                        <div>
                                            <label class="mb-2 sm:mb-3 form-label">Coverage Quality <span class="text-red-400">*</span></label>
                                            <div class="flex gap-2 sm:gap-3 star-rating" data-rating="coverage">
                                                <span class="star" data-value="1"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="2"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="3"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="4"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="5"><i class="fas fa-star"></i></span>
                                            </div>
                                            <p class="field-hint"><span class="coverage-text">Select rating</span></p>
                                            <input type="hidden" name="coverage">
                                        </div>

                                        <div>
                                            <label class="mb-2 sm:mb-3 form-label">Community Impact <span class="text-red-400">*</span></label>
                                            <div class="flex gap-2 sm:gap-3 star-rating" data-rating="impact">
                                                <span class="star" data-value="1"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="2"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="3"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="4"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="5"><i class="fas fa-star"></i></span>
                                            </div>
                                            <p class="field-hint"><span class="impact-text">Select rating</span></p>
                                            <input type="hidden" name="impact">
                                        </div>

                                        <div>
                                            <label class="mb-2 sm:mb-3 form-label">Overall Satisfaction <span class="text-red-400">*</span></label>
                                            <div class="flex gap-2 sm:gap-3 star-rating" data-rating="overall">
                                                <span class="star" data-value="1"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="2"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="3"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="4"><i class="fas fa-star"></i></span>
                                                <span class="star" data-value="5"><i class="fas fa-star"></i></span>
                                            </div>
                                            <p class="field-hint"><span class="overall-text">Select rating</span></p>
                                            <input type="hidden" name="overall" required>
                                        </div>

                                        <div class="flex flex-col-reverse justify-between gap-2 pt-4 border-t sm:flex-row sm:gap-3 sm:pt-6 border-slate-700/30">
                                            <button type="button" class="tab-prev btn-outline">
                                                <i class="mr-1 sm:mr-2 fas fa-arrow-left"></i>Back
                                            </button>
                                            <button type="button" class="tab-next btn-gradient">
                                                Next <i class="ml-1 sm:ml-2 fas fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 3: STORY CONTENT -->
                                <div class="hidden tab-content" id="tab-3">
                                    <div class="space-y-4 sm:space-y-5">
                                        <div>
                                            <label class="form-label">Story Title <span class="text-red-400">*</span></label>
                                            <input type="text" id="title" placeholder="e.g., How Atannex Changed Our Community" class="form-input" maxlength="100" required>
                                            <p class="field-hint"><i class="fas fa-lightbulb"></i>A compelling title helps your story stand out</p>
                                        </div>

                                        <div>
                                            <label class="form-label">Your Story <span class="text-red-400">*</span></label>
                                            <textarea id="message" rows="7" placeholder="Share your experience with Atannex. What changed? What impact did it have? Be specific and authentic..." class="form-textarea" required></textarea>
                                            <div class="flex justify-between mt-2 text-xs text-slate-400">
                                                <span><i class="text-green-400 fas fa-check-circle"></i> Min 50 characters</span>
                                                <span id="charCount">0 / 1000</span>
                                            </div>
                                        </div>

                                        <div class="info-box info-box-blue">
                                            <i class="fas fa-lightbulb"></i>
                                            <div>
                                                <p class="mb-1 text-xs font-semibold sm:text-sm">Pro tips for great stories:</p>
                                                <ul class="text-xs space-y-0.5 list-disc list-inside">
                                                    <li>Be specific about impact and outcomes</li>
                                                    <li>Share concrete examples and numbers</li>
                                                    <li>Be authentic and personal</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="flex flex-col-reverse justify-between gap-2 pt-4 border-t sm:flex-row sm:gap-3 sm:pt-6 border-slate-700/30">
                                            <button type="button" class="tab-prev btn-outline">
                                                <i class="mr-1 sm:mr-2 fas fa-arrow-left"></i>Back
                                            </button>
                                            <button type="button" class="tab-next btn-gradient">
                                                Next <i class="ml-1 sm:ml-2 fas fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 4: SUBMISSION & CONSENT -->
                                <div class="hidden tab-content" id="tab-4">
                                    <div class="space-y-4 sm:space-y-5">
                                        <div>
                                            <label class="form-label">Add a Photo (Optional)</label>
                                            <div class="photo-drop-zone" id="photoDropZone">
                                                <i class="block mb-3 text-4xl sm:mb-4 text-slate-500 fas fa-cloud-arrow-up"></i>
                                                <p class="mb-1 text-sm font-semibold text-white sm:text-base">Drop your photo or click to browse</p>
                                                <p class="text-xs text-slate-400">JPG, PNG • Max 5MB</p>
                                                <input type="file" id="photo" accept="image/*" class="hidden">
                                            </div>
                                            <div id="photoPreview" class="hidden mt-3 sm:mt-4">
                                                <p class="mb-2 text-xs text-slate-400">Selected:</p>
                                                <div class="flex items-center justify-between p-3 border rounded-lg sm:p-4 bg-slate-800/50 border-slate-700/50">
                                                    <span id="photoName" class="text-xs text-white truncate sm:text-sm"></span>
                                                    <button type="button" id="removePhoto" class="flex-shrink-0 ml-2 text-xs font-semibold text-red-400 hover:text-red-300">Remove</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-3">
                                            <label class="flex items-start gap-3 p-3 transition rounded-lg cursor-pointer hover:bg-slate-800/30">
                                                <input type="checkbox" id="consent" class="flex-shrink-0 w-5 h-5 mt-0.5 accent-blue-500" required>
                                                <span class="text-xs leading-relaxed sm:text-sm text-slate-300">I consent to publish my testimonial and confirm it is authentic and my own work</span>
                                            </label>

                                            <label class="flex items-start gap-3 p-3 transition rounded-lg cursor-pointer hover:bg-slate-800/30">
                                                <input type="checkbox" id="newsletter" class="flex-shrink-0 w-5 h-5 mt-0.5 accent-blue-500">
                                                <span class="text-xs sm:text-sm text-slate-300">Send me updates and stories from Atannex</span>
                                            </label>
                                        </div>

                                        <div class="info-box info-box-green">
                                            <i class="fas fa-info-circle"></i>
                                            <div>
                                                <p class="text-xs sm:text-sm"><strong>What happens next?</strong> Your testimonial will be reviewed in 5-7 business days. You'll receive an email confirmation when it's published.</p>
                                            </div>
                                        </div>

                                        <div class="flex flex-col-reverse justify-between gap-2 pt-4 border-t sm:flex-row sm:gap-3 sm:pt-6 border-slate-700/30">
                                            <button type="button" class="tab-prev btn-outline">
                                                <i class="mr-1 sm:mr-2 fas fa-arrow-left"></i>Back
                                            </button>
                                            <button type="submit" class="btn-gradient">
                                                <i class="mr-1 sm:mr-2 fas fa-paper-plane"></i>Submit Your Story
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- STATS SECTION -->
                        <div class="grid grid-cols-2 gap-3 mt-6 sm:gap-4">
                            <div class="stat-card">
                                <div class="stat-number">450+</div>
                                <p class="stat-label">Testimonials</p>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">50+</div>
                                <p class="stat-label">Countries</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SHOWCASE SECTION -->
    <section class="px-4 py-16 sm:py-24 md:py-32 sm:px-6 md:px-8 bg-gradient-to-b from-slate-950/50 to-slate-900">
        <div class="mx-auto max-w-7xl">
            <!-- SECTION HEADER -->
            <div class="max-w-3xl mx-auto mb-12 text-center sm:mb-16">
                <div class="justify-center mx-auto mb-4 badge-primary sm:mb-6 w-fit">
                    <i class="fas fa-quote-left"></i>
                    <span>Community Voices</span>
                </div>
                <h2 class="mb-4 text-2xl font-bold text-white sm:mb-6 sm:text-3xl md:text-4xl">
                    See What Our Community Says
                </h2>
                <p class="text-sm sm:text-lg text-slate-300">
                    Join 450+ voices from 50+ countries sharing their impact stories
                </p>
            </div>

            <!-- FILTERS -->
            <div class="flex flex-wrap justify-center gap-2 mb-8 sm:gap-3 sm:mb-12">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-star"></i>
                    <span>All Stories</span>
                </button>
                <button class="filter-btn" data-filter="leader">
                    <i class="fas fa-user-tie"></i>
                    <span class="hidden sm:inline">Community</span> Leader
                </button>
                <button class="filter-btn" data-filter="educator">
                    <i class="fas fa-book"></i>
                    <span>Educator</span>
                </button>
                <button class="filter-btn" data-filter="entrepreneur">
                    <i class="fas fa-briefcase"></i>
                    <span>Business</span>
                </button>
                <button class="filter-btn" data-filter="diaspora">
                    <i class="fas fa-globe"></i>
                    <span>Diaspora</span>
                </button>
                <button class="filter-btn" data-filter="health">
                    <i class="fas fa-heartbeat"></i>
                    <span>Health</span>
                </button>
            </div>

            <!-- TESTIMONIALS GRID -->
            <div class="grid grid-cols-1 gap-6 sm:gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- TESTIMONIAL CARD 1 -->
                <div class="testimonial-card" data-category="leader">
                    <div class="flex items-start justify-between gap-2 mb-4 sm:mb-5">
                        <div class="flex gap-0.5 sm:gap-1 text-yellow-400 star-rating-icon">
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold text-blue-300 rounded-full bg-blue-500/20 border border-blue-500/30">Leader</span>
                    </div>
                    <h3 class="mb-3 text-base font-bold text-white sm:text-lg">Our Voice Was Amplified</h3>
                    <p class="flex-grow mb-5 text-xs italic leading-relaxed sm:text-sm text-slate-300">"Atannex gave our school the platform to share our educational initiatives. The coverage brought recognition and support from unexpected corners."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop" alt="Eveline Che" class="user-avatar">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-white truncate sm:text-sm">Eveline Che</p>
                            <p class="text-xs text-slate-400">School Principal, Lebialem</p>
                        </div>
                    </div>
                </div>

                <!-- TESTIMONIAL CARD 2 -->
                <div class="testimonial-card" data-category="diaspora">
                    <div class="flex items-start justify-between gap-2 mb-4 sm:mb-5">
                        <div class="flex gap-0.5 sm:gap-1 text-yellow-400 star-rating-icon">
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold text-cyan-300 rounded-full bg-cyan-500/20 border border-cyan-500/30">Diaspora</span>
                    </div>
                    <h3 class="mb-3 text-base font-bold text-white sm:text-lg">Connected to Home</h3>
                    <p class="flex-grow mb-5 text-xs italic leading-relaxed sm:text-sm text-slate-300">"Living in the UK, Atannex keeps me meaningfully connected to my community. Now I can contribute in ways that matter."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Julius Tanjoh" class="user-avatar">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-white truncate sm:text-sm">Julius Tanjoh</p>
                            <p class="text-xs text-slate-400">Diaspora Member, UK</p>
                        </div>
                    </div>
                </div>

                <!-- TESTIMONIAL CARD 3 -->
                <div class="testimonial-card" data-category="entrepreneur">
                    <div class="flex items-start justify-between gap-2 mb-4 sm:mb-5">
                        <div class="flex gap-0.5 sm:gap-1 text-yellow-400 star-rating-icon">
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold text-green-300 rounded-full bg-green-500/20 border border-green-500/30">Business</span>
                    </div>
                    <h3 class="mb-3 text-base font-bold text-white sm:text-lg">Business Growth Unlocked</h3>
                    <p class="flex-grow mb-5 text-xs italic leading-relaxed sm:text-sm text-slate-300">"The coverage opened doors we didn't know existed. We've landed partnerships and expanded our reach exponentially."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop" alt="Samuel Forbi" class="user-avatar">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-white truncate sm:text-sm">Samuel Forbi</p>
                            <p class="text-xs text-slate-400">Entrepreneur, Lebialem</p>
                        </div>
                    </div>
                </div>

                <!-- TESTIMONIAL CARD 4 -->
                <div class="testimonial-card" data-category="leader">
                    <div class="flex items-start justify-between gap-2 mb-4 sm:mb-5">
                        <div class="flex gap-0.5 sm:gap-1 text-yellow-400 star-rating-icon">
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold text-yellow-300 rounded-full bg-yellow-500/20 border border-yellow-500/30">Leader</span>
                    </div>
                    <h3 class="mb-3 text-base font-bold text-white sm:text-lg">Respect Restored</h3>
                    <p class="flex-grow mb-5 text-xs italic leading-relaxed sm:text-sm text-slate-300">"Our farming heritage was showcased to the world. This transformed how our community is perceived and valued globally."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop" alt="Mama Beatrice" class="user-avatar">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-white truncate sm:text-sm">Mama Beatrice</p>
                            <p class="text-xs text-slate-400">Community Leader, Farmer</p>
                        </div>
                    </div>
                </div>

                <!-- TESTIMONIAL CARD 5 -->
                <div class="testimonial-card" data-category="educator">
                    <div class="flex items-start justify-between gap-2 mb-4 sm:mb-5">
                        <div class="flex gap-0.5 sm:gap-1 text-yellow-400 star-rating-icon">
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold text-purple-300 rounded-full bg-purple-500/20 border border-purple-500/30">Educator</span>
                    </div>
                    <h3 class="mb-3 text-base font-bold text-white sm:text-lg">Inspiring the Next Generation</h3>
                    <p class="flex-grow mb-5 text-xs italic leading-relaxed sm:text-sm text-slate-300">"Atannex showed me the transformative power of storytelling. It set the standard for how we should engage with our communities."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop" alt="Celestine Ncho" class="user-avatar">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-white truncate sm:text-sm">Celestine Ncho</p>
                            <p class="text-xs text-slate-400">Journalist, Educator</p>
                        </div>
                    </div>
                </div>

                <!-- TESTIMONIAL CARD 6 -->
                <div class="testimonial-card" data-category="health">
                    <div class="flex items-start justify-between gap-2 mb-4 sm:mb-5">
                        <div class="flex gap-0.5 sm:gap-1 text-yellow-400 star-rating-icon">
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold text-red-300 rounded-full bg-red-500/20 border border-red-500/30">Health</span>
                    </div>
                    <h3 class="mb-3 text-base font-bold text-white sm:text-lg">Lives Saved Through Coverage</h3>
                    <p class="flex-grow mb-5 text-xs italic leading-relaxed sm:text-sm text-slate-300">"The health awareness coverage directly saved lives in our community through early disease detection and prevention."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Dr. Raphael Tabe" class="user-avatar">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-white truncate sm:text-sm">Dr. Raphael Tabe</p>
                            <p class="text-xs text-slate-400">Health Worker, MD</p>
                        </div>
                    </div>
                </div>

                <!-- TESTIMONIAL CARD 7 -->
                <div class="testimonial-card" data-category="entrepreneur">
                    <div class="flex items-start justify-between gap-2 mb-4 sm:mb-5">
                        <div class="flex gap-0.5 sm:gap-1 text-yellow-400 star-rating-icon">
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold text-green-300 rounded-full bg-green-500/20 border border-green-500/30">Business</span>
                    </div>
                    <h3 class="mb-3 text-base font-bold text-white sm:text-lg">International Recognition</h3>
                    <p class="flex-grow mb-5 text-xs italic leading-relaxed sm:text-sm text-slate-300">"Our startup gained international visibility through Atannex coverage. Investors took notice and opportunities multiplied."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop" alt="Alex Ndinga" class="user-avatar">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-white truncate sm:text-sm">Alex Ndinga</p>
                            <p class="text-xs text-slate-400">Tech Entrepreneur</p>
                        </div>
                    </div>
                </div>

                <!-- TESTIMONIAL CARD 8 -->
                <div class="testimonial-card" data-category="diaspora">
                    <div class="flex items-start justify-between gap-2 mb-4 sm:mb-5">
                        <div class="flex gap-0.5 sm:gap-1 text-yellow-400 star-rating-icon">
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold text-cyan-300 rounded-full bg-cyan-500/20 border border-cyan-500/30">Diaspora</span>
                    </div>
                    <h3 class="mb-3 text-base font-bold text-white sm:text-lg">Heritage Preserved</h3>
                    <p class="flex-grow mb-5 text-xs italic leading-relaxed sm:text-sm text-slate-300">"As a diaspora member, Atannex helped me pass down my cultural heritage to my children in a meaningful way."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Margaret Nguh" class="user-avatar">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-white truncate sm:text-sm">Margaret Nguh</p>
                            <p class="text-xs text-slate-400">Diaspora, USA</p>
                        </div>
                    </div>
                </div>

                <!-- TESTIMONIAL CARD 9 -->
                <div class="testimonial-card" data-category="educator">
                    <div class="flex items-start justify-between gap-2 mb-4 sm:mb-5">
                        <div class="flex gap-0.5 sm:gap-1 text-yellow-400 star-rating-icon">
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                            <i class="text-sm fas fa-star"></i>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold text-purple-300 rounded-full bg-purple-500/20 border border-purple-500/30">Educator</span>
                    </div>
                    <h3 class="mb-3 text-base font-bold text-white sm:text-lg">Youth Empowerment</h3>
                    <p class="flex-grow mb-5 text-xs italic leading-relaxed sm:text-sm text-slate-300">"Atannex's coverage of our youth program attracted sponsors and mentors who have transformed young lives in our community."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-700/30">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop" alt="Teacher Monica" class="user-avatar">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-white truncate sm:text-sm">Monica Ncha</p>
                            <p class="text-xs text-slate-400">Teacher, Youth Director</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LOAD MORE BUTTON -->
            <div class="flex justify-center mt-12 sm:mt-16">
                <button class="px-8 py-3 text-sm font-semibold text-white transition border-2 rounded-lg sm:px-10 sm:py-3.5 sm:text-base border-blue-500/50 hover:border-blue-400 hover:bg-blue-500/10">
                    <i class="mr-2 fas fa-arrow-down"></i>
                    Load More Stories
                </button>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="px-4 py-12 border-t sm:py-16 sm:px-6 md:px-8 border-slate-700/20">
        <div class="mx-auto max-w-7xl">
            <div class="grid grid-cols-2 gap-6 mb-8 sm:gap-8 sm:grid-cols-2 md:grid-cols-4">
                <div>
                    <h4 class="mb-3 text-xs font-bold text-white sm:mb-4 sm:text-sm">Atannex</h4>
                    <p class="text-xs text-slate-400">Digital news for Lebialem.</p>
                </div>
                <div>
                    <h4 class="mb-3 text-xs font-bold text-white sm:mb-4 sm:text-sm">Content</h4>
                    <ul class="space-y-1.5 sm:space-y-2 text-xs text-slate-400">
                        <li><a href="/#stories" class="transition hover:text-white">Stories</a></li>
                        <li><a href="/testimonials" class="transition hover:text-white">Testimonials</a></li>
                        <li><a href="/#coverage" class="transition hover:text-white">Coverage</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-3 text-xs font-bold text-white sm:mb-4 sm:text-sm">Company</h4>
                    <ul class="space-y-1.5 sm:space-y-2 text-xs text-slate-400">
                        <li><a href="/#about" class="transition hover:text-white">About</a></li>
                        <li><a href="/advertise" class="transition hover:text-white">Advertise</a></li>
                        <li><a href="/careers" class="transition hover:text-white">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-3 text-xs font-bold text-white sm:mb-4 sm:text-sm">Legal</h4>
                    <ul class="space-y-1.5 sm:space-y-2 text-xs text-slate-400">
                        <li><a href="/privacy" class="transition hover:text-white">Privacy</a></li>
                        <li><a href="/terms" class="transition hover:text-white">Terms</a></li>
                        <li><a href="mailto:hello@atannex.cm" class="transition hover:text-white">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 text-xs text-center border-t text-slate-400 border-slate-700/20">
                <p>&copy; 2024 Atannex. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Tab Navigation
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        const progressDots = document.querySelectorAll('.progress-dot');

        function showTab(tabId) {
            tabContents.forEach(tab => tab.classList.remove('active'));
            tabButtons.forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            document.querySelector(`[data-tab="${tabId}"]`).classList.add('active');

            const tabNum = parseInt(tabId.split('-')[1]);
            progressDots.forEach((dot, idx) => {
                if (idx + 1 === tabNum) {
                    dot.classList.add('active');
                    dot.classList.remove('completed');
                } else if (idx + 1 < tabNum) {
                    dot.classList.add('completed');
                    dot.classList.remove('active');
                } else {
                    dot.classList.remove('active', 'completed');
                }
            });
        }

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                showTab(tabId);
            });
        });

        progressDots.forEach(dot => {
            dot.addEventListener('click', () => {
                const tabId = `tab-${dot.getAttribute('data-tab')}`;
                showTab(tabId);
            });
        });

        document.querySelectorAll('.tab-next').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const currentTab = btn.closest('.tab-content');
                const tabNumber = parseInt(currentTab.id.split('-')[1]);
                const nextTabId = `tab-${tabNumber + 1}`;
                if (document.getElementById(nextTabId)) showTab(nextTabId);
            });
        });

        document.querySelectorAll('.tab-prev').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const currentTab = btn.closest('.tab-content');
                const tabNumber = parseInt(currentTab.id.split('-')[1]);
                const prevTabId = `tab-${tabNumber - 1}`;
                if (document.getElementById(prevTabId)) showTab(prevTabId);
            });
        });

        // Star Ratings
        document.querySelectorAll('.star-rating').forEach(ratingGroup => {
            const ratingType = ratingGroup.getAttribute('data-rating');
            const stars = ratingGroup.querySelectorAll('.star');
            let selectedRating = 0;

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    selectedRating = star.getAttribute('data-value');
                    document.querySelector(`input[name="${ratingType}"]`).value = selectedRating;
                    updateStars(ratingGroup, selectedRating);
                    document.querySelector(`.${ratingType}-text`).textContent = `${selectedRating} out of 5`;
                });

                star.addEventListener('mouseover', () => updateStars(ratingGroup, star.getAttribute('data-value')));
            });

            ratingGroup.addEventListener('mouseleave', () => updateStars(ratingGroup, selectedRating));
        });

        function updateStars(ratingGroup, value) {
            ratingGroup.querySelectorAll('.star').forEach(star => {
                if (star.getAttribute('data-value') <= value) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        // Character Counter
        document.getElementById('message').addEventListener('input', (e) => {
            document.getElementById('charCount').textContent = `${e.target.value.length} / 1000`;
        });

        // Photo Upload
        const photoDropZone = document.getElementById('photoDropZone');
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photoPreview');
        const photoName = document.getElementById('photoName');
        const removePhotoBtn = document.getElementById('removePhoto');

        photoDropZone.addEventListener('click', () => photoInput.click());
        photoDropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            photoDropZone.style.borderColor = '#3b82f6';
        });
        photoDropZone.addEventListener('dragleave', () => {
            photoDropZone.style.borderColor = '';
        });
        photoDropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            photoDropZone.style.borderColor = '';
            if (e.dataTransfer.files[0]) {
                photoInput.files = e.dataTransfer.files;
                updatePhotoPreview();
            }
        });
        photoInput.addEventListener('change', updatePhotoPreview);

        function updatePhotoPreview() {
            if (photoInput.files[0]) {
                photoName.textContent = photoInput.files[0].name;
                photoDropZone.style.display = 'none';
                photoPreview.classList.remove('hidden');
            }
        }

        removePhotoBtn.addEventListener('click', (e) => {
            e.preventDefault();
            photoInput.value = '';
            photoDropZone.style.display = '';
            photoPreview.classList.add('hidden');
        });

        // Testimonials Filter
        const filterBtns = document.querySelectorAll('.filter-btn');
        const testimonialCards = document.querySelectorAll('.testimonial-card');

        // Initialize all cards as visible on page load
        window.addEventListener('load', () => {
            testimonialCards.forEach(card => {
                card.classList.add('visible');
            });
        });

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const filterValue = btn.getAttribute('data-filter');

                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                testimonialCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');

                    if (filterValue === 'all' || cardCategory === filterValue) {
                        card.classList.remove('hidden');
                        card.classList.add('visible');
                    } else {
                        card.classList.add('hidden');
                        card.classList.remove('visible');
                    }
                });
            });
        });


        // Form Submission
        const form = document.getElementById('testimonialForm');
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const fullName = document.getElementById('fullName').value;
            const email = document.getElementById('email').value;
            const location = document.getElementById('location').value;
            const role = document.getElementById('role').value;
            const title = document.getElementById('title').value;
            const message = document.getElementById('message').value;
            const consent = document.getElementById('consent').checked;
            const coverage = document.querySelector('input[name="coverage"]').value;
            const impact = document.querySelector('input[name="impact"]').value;
            const overall = document.querySelector('input[name="overall"]').value;

            if (!fullName || !email || !location || !role || !title || !message || !consent || !coverage || !impact || !overall) {
                errorText.textContent = 'Please complete all required fields and accept consent.';
                errorMessage.classList.add('show');
                setTimeout(() => errorMessage.classList.remove('show'), 5000);
                return;
            }

            if (message.length < 50) {
                errorText.textContent = 'Your story must be at least 50 characters long.';
                errorMessage.classList.add('show');
                setTimeout(() => errorMessage.classList.remove('show'), 5000);
                return;
            }

            console.log('Submitted:', {
                fullName
                , email
                , location
                , role
                , title
                , message
                , coverage
                , impact
                , overall
            });

            successMessage.classList.add('show');
            form.reset();
            showTab('tab-1');
            document.querySelectorAll('input[name="coverage"], input[name="impact"], input[name="overall"]').forEach(input => input.value = '');
            photoDropZone.style.display = '';
            photoPreview.classList.add('hidden');

            setTimeout(() => successMessage.classList.remove('show'), 6000);
        });

    </script>

</body>
</html>
