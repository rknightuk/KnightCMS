import './bootstrap';

(function () {
    window.highlightInvalidInputs = (f) => {
        f.querySelectorAll('input').forEach(input => {
            console.log(input.checkValidity())
            input.checkValidity() ? input.classList.remove('invalid-form-input') : input.classList.add('invalid-form-input')
        });
    }

    String.prototype.toTitleCase = function () {
        'use strict'
        var smallWords = /^(a|an|and|as|at|but|by|en|for|if|in|nor|of|on|or|per|the|to|v.?|vs.?|via)$/i
        var alphanumericPattern = /([A-Za-z0-9\u00C0-\u00FF])/
        var wordSeparators = /([ :–—-])/

        return this.split(wordSeparators)
            .map(function (current, index, array) {
                if (
                    /* Check for small words */
                    current.search(smallWords) > -1 &&
                    /* Skip first and last word */
                    index !== 0 &&
                    index !== array.length - 1 &&
                    /* Ignore title end and subtitle start */
                    array[index - 3] !== ':' &&
                    array[index + 1] !== ':' &&
                    /* Ignore small words that start a hyphenated phrase */
                    (array[index + 1] !== '-' ||
                        (array[index - 1] === '-' && array[index + 1] === '-'))
                ) {
                    return current.toLowerCase()
                }

                /* Ignore intentional capitalization */
                if (current.substr(1).search(/[A-Z]|\../) > -1) {
                    return current
                }

                /* Ignore URLs */
                if (array[index + 1] === ':' && array[index + 2] !== '') {
                    return current
                }

                /* Capitalize the first letter */
                return current.replace(alphanumericPattern, function (match) {
                    return match.toUpperCase()
                })
            })
            .join('')
    }
    const title = document.getElementById('title')
    if (title) {
        const handleTitleChange = () => {
            document.getElementById('title').value = document.getElementById('title').value.toTitleCase()
            document.getElementById('permalink').value = document.getElementById('title').value.toLowerCase().replace(/[^\w\s]/gi, '').split(' ').join('-')
        }
        title.addEventListener('keyup', handleTitleChange)
        title.addEventListener('change', handleTitleChange)
    }


    // menu nonsense
    function getElements() {
        return {
            layout: document.getElementById('layout'),
            menu: document.getElementById('menu'),
            menuLink: document.getElementById('menuLink')
        };
    }

    function toggleClass(element, className) {
        var classes = element.className.split(/\s+/);
        var length = classes.length;
        var i = 0;

        for (; i < length; i++) {
            if (classes[i] === className) {
                classes.splice(i, 1);
                break;
            }
        }
        // The className is not found
        if (length === classes.length) {
            classes.push(className);
        }

        element.className = classes.join(' ');
    }

    function toggleAll() {
        var active = 'active';
        var elements = getElements();

        toggleClass(elements.layout, active);
        toggleClass(elements.menu, active);
        toggleClass(elements.menuLink, active);
    }

    function handleEvent(e) {
        var elements = getElements();

        if (e.target.id === elements.menuLink.id || e.target.id === 'burger') {
            toggleAll();
            e.preventDefault();
        } else if (elements.menu.className.indexOf('active') !== -1) {
            toggleAll();
        }
    }

    document.addEventListener('click', handleEvent);

}());
