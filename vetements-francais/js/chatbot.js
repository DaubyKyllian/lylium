// Assistante Lylium — mini chatbot de questions fréquentes.
// Aucun appel réseau ni IA : les questions et réponses ci-dessous sont
// toutes codées en dur (FAQ_ITEMS). Le bouton apparaît après un certain
// scroll, puis l'utilisateur choisit une question (puces) ou tape un mot-clé,
// qui est comparé aux mots-clés programmés pour retrouver la bonne réponse.
(function () {
    var FAQ_ITEMS = [
        {
            id: 'livraison',
            chip: 'Livraison',
            question: 'Quels sont les délais et frais de livraison ?',
            answer: "Comptez 3 à 5 jours ouvrés en France métropolitaine. La livraison est offerte dès 120&nbsp;€ d'achat, 6,90&nbsp;€ en dessous.",
            keywords: ['livraison', 'delai', 'delais', 'frais', 'expedition', 'envoi', 'colis']
        },
        {
            id: 'retour',
            chip: 'Retours',
            question: 'Puis-je retourner un article ?',
            answer: "Oui, sous 30 jours à compter de la réception, dans son état d'origine et non porté. Le retour est gratuit depuis la France métropolitaine. Détails sur la <a href=\"/politique-livraison-retours.php\">politique de retours</a>.",
            keywords: ['retour', 'retourner', 'rembours', 'renvoyer', 'renvoi', 'echange']
        },
        {
            id: 'taille',
            chip: 'Tailles',
            question: 'Comment choisir ma taille ?',
            answer: "Nos coupes sont ajustées et fidèles aux tailles françaises standards. En cas de doute entre deux tailles, nous conseillons de prendre la taille au-dessus.",
            keywords: ['taille', 'tailles', 'pointure', 'morphologie', 'xs', 'grand', 'petit']
        },
        {
            id: 'entretien',
            chip: 'Entretien',
            question: 'Comment entretenir mes vêtements Lylium ?',
            answer: "Lavage à 30°C, à l'envers, sans essorage excessif. Nos matières naturelles préfèrent le séchage à l'air libre à celui en machine.",
            keywords: ['entretien', 'laver', 'lavage', 'nettoyer', 'repassage', 'seche']
        },
        {
            id: 'paiement',
            chip: 'Paiement',
            question: 'Quels moyens de paiement acceptez-vous ?',
            answer: "Le paiement en ligne sécurisé arrive prochainement directement sur le site. En attendant, <a href=\"/contact.php\">contactez-nous</a> pour finaliser une commande.",
            keywords: ['paiement', 'payer', 'carte', 'cb', 'paypal', 'virement']
        },
        {
            id: 'compte',
            chip: 'Mon compte',
            question: 'Comment retrouver mes favoris ou mes commandes ?',
            answer: "Connectez-vous depuis votre <a href=\"/connexion.php\">espace compte</a> pour retrouver vos <a href=\"/favoris.php\">favoris</a> et le suivi de vos commandes.",
            keywords: ['compte', 'favoris', 'connexion', 'inscription', 'mot de passe', 'commande', 'commandes']
        },
        {
            id: 'retractation',
            chip: 'Rétractation',
            question: "Puis-je annuler ma commande après l'avoir passée ?",
            answer: "Vous disposez d'un délai légal de 14 jours à compter de la réception pour exercer votre droit de rétractation, via notre <a href=\"/retractation.php\">formulaire dédié</a>.",
            keywords: ['annuler', 'retractation', 'retracter', '14 jours', 'droit']
        },
        {
            id: 'contact',
            chip: 'Nous contacter',
            question: 'Comment puis-je vous contacter ?',
            answer: "Par e-mail à <a href=\"mailto:contact@lylium.fr\">contact@lylium.fr</a>, ou en atelier au 12 rue des Ateliers, 59000 Lille (lun.–ven., 9h–18h). Vous pouvez aussi utiliser notre <a href=\"/contact.php\">formulaire de contact</a>.",
            keywords: ['contact', 'telephone', 'email', 'mail', 'adresse', 'horaires', 'joindre', 'atelier']
        },
        {
            id: 'baise',
            question: 'On baise ?',
            answer: "Oui !!!!",
            keywords: ['baise', 'ken']
        }
    ];

    var SCROLL_REVEAL = 320;
    var SEEN_KEY = 'lylium_chatbot_seen';

    function normalize(str) {
        return str.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
    }

    function matchFaq(text) {
        var normalized = normalize(text);
        var best = null;
        var bestScore = 0;
        FAQ_ITEMS.forEach(function (item) {
            var score = 0;
            item.keywords.forEach(function (kw) {
                if (normalized.indexOf(normalize(kw)) !== -1) score++;
            });
            if (score > bestScore) {
                bestScore = score;
                best = item;
            }
        });
        return best;
    }

    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'chatbot-toggle';
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-controls', 'chatbotPanel');
        toggle.setAttribute('aria-label', "Ouvrir l'assistante Lylium");
        toggle.innerHTML =
            '<svg class="chatbot-icon-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">' +
                '<path d="M4 5.5h16a1 1 0 0 1 1 1V16a1 1 0 0 1-1 1H9l-4.2 3.2a.6.6 0 0 1-.96-.48V17H4a1 1 0 0 1-1-1V6.5a1 1 0 0 1 1-1Z"/>' +
                '<circle cx="8.3" cy="11.3" r=".9" fill="currentColor" stroke="none"/>' +
                '<circle cx="12" cy="11.3" r=".9" fill="currentColor" stroke="none"/>' +
                '<circle cx="15.7" cy="11.3" r=".9" fill="currentColor" stroke="none"/>' +
            '</svg>' +
            '<svg class="chatbot-icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">' +
                '<path d="M6 6l12 12M18 6L6 18"/>' +
            '</svg>' +
            '<span class="chatbot-toggle-badge"></span>';

        var panel = document.createElement('div');
        panel.className = 'chatbot-panel';
        panel.id = 'chatbotPanel';
        panel.setAttribute('role', 'dialog');
        panel.setAttribute('aria-label', "Assistante Lylium");
        panel.innerHTML =
            '<div class="chatbot-header">' +
                '<span class="chatbot-header-avatar" aria-hidden="true">L</span>' +
                '<span class="chatbot-header-text">' +
                    '<span class="chatbot-header-title">Assistante Lylium</span>' +
                    '<span class="chatbot-header-status">En ligne</span>' +
                '</span>' +
            '</div>' +
            '<div class="chatbot-messages" aria-live="polite"></div>' +
            '<div class="chatbot-chips"></div>' +
            '<form class="chatbot-form">' +
                '<input type="text" placeholder="Posez votre question…" aria-label="Votre question">' +
                '<button type="submit" aria-label="Envoyer">' +
                    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 12h15M13 6l6 6-6 6"/></svg>' +
                '</button>' +
            '</form>';

        document.body.appendChild(toggle);
        document.body.appendChild(panel);

        var messagesEl = panel.querySelector('.chatbot-messages');
        var chipsEl = panel.querySelector('.chatbot-chips');
        var formEl = panel.querySelector('.chatbot-form');
        var inputEl = formEl.querySelector('input');
        var badgeEl = toggle.querySelector('.chatbot-toggle-badge');
        var started = false;

        function scrollToBottom() {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function addMessage(kind, html) {
            var bubble = document.createElement('div');
            bubble.className = 'chatbot-msg chatbot-msg--' + kind;
            bubble.innerHTML = html;
            messagesEl.appendChild(bubble);
            scrollToBottom();
            return bubble;
        }

        function renderChips(includeAll) {
            chipsEl.innerHTML = '';
            FAQ_ITEMS.forEach(function (item) {
                var chip = document.createElement('button');
                chip.type = 'button';
                chip.className = 'chatbot-chip';
                chip.textContent = item.chip;
                chip.addEventListener('click', function () { askFaq(item); });
                chipsEl.appendChild(chip);
            });
        }

        function askFaq(item) {
            addMessage('user', item.question);
            chipsEl.innerHTML = '';
            var typing = addMessage('typing', '<span></span><span></span><span></span>');
            setTimeout(function () {
                typing.remove();
                addMessage('bot', item.answer);
                renderChips();
            }, 480 + Math.random() * 260);
        }

        function respondToFreeText(text) {
            addMessage('user', text.replace(/</g, '&lt;'));
            chipsEl.innerHTML = '';
            var match = matchFaq(text);
            var typing = addMessage('typing', '<span></span><span></span><span></span>');
            setTimeout(function () {
                typing.remove();
                if (match) {
                    addMessage('bot', match.answer);
                } else {
                    addMessage('bot', "Je n'ai pas encore de réponse toute prête pour ça. Voici ce que je sais traiter, ou écrivez-nous directement à <a href=\"mailto:contact@lylium.fr\">contact@lylium.fr</a>.");
                }
                renderChips();
            }, 480 + Math.random() * 260);
        }

        function openPanel() {
            panel.classList.add('is-open');
            toggle.setAttribute('aria-expanded', 'true');
            badgeEl.setAttribute('hidden', '');
            try { sessionStorage.setItem(SEEN_KEY, '1'); } catch (e) {}
            if (!started) {
                started = true;
                addMessage('bot', "Bonjour&nbsp;👋 Je suis l'assistante Lylium. Choisissez une question ci-dessous, ou écrivez la vôtre.");
                renderChips();
            }
            inputEl.focus();
        }

        function closePanel() {
            panel.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        }

        toggle.addEventListener('click', function () {
            if (panel.classList.contains('is-open')) closePanel();
            else openPanel();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && panel.classList.contains('is-open')) closePanel();
        });

        formEl.addEventListener('submit', function (e) {
            e.preventDefault();
            var value = inputEl.value.trim();
            if (!value) return;
            inputEl.value = '';
            respondToFreeText(value);
        });

        try {
            if (sessionStorage.getItem(SEEN_KEY)) badgeEl.setAttribute('hidden', '');
        } catch (e) {}

        var ticking = false;
        function updateVisibility() {
            ticking = false;
            if (window.scrollY > SCROLL_REVEAL) {
                toggle.classList.add('is-visible');
            } else if (!panel.classList.contains('is-open')) {
                toggle.classList.remove('is-visible');
            }
        }
        window.addEventListener('scroll', function () {
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(updateVisibility);
            }
        }, { passive: true });
        updateVisibility();
    });
})();
