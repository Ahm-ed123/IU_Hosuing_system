function googleTranslateElementInit() {
    new google.translate.TranslateElement({
      pageLanguage: 'ar',  // اللغة الأصلية للموقع (العربية)
      includedLanguages: 'ar,en,fr,de',  // اللغات المدعومة
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
  }
  