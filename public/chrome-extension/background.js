// Escuchar mensajes del content script o popup
chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {
  if (request.action === 'analyzePage') {
    // Aquí podríamos realizar análisis en segundo plano si es necesario
    // Por ahora, simplemente reenviamos el mensaje al content script
    chrome.tabs.query({active: true, currentWindow: true}, function(tabs) {
      chrome.tabs.sendMessage(tabs[0].id, {action: 'extractMetaTags'}, function(response) {
        sendResponse(response);
      });
    });
    
    // Devolver true para indicar que usaremos sendResponse de forma asíncrona
    return true;
  }
});

// Escuchar cuando se instala la extensión
chrome.runtime.onInstalled.addListener(function() {
  console.log('Extensión SEO Meta Tags Analyzer instalada correctamente');
  
  // Crear un menú contextual para análisis rápido
  chrome.contextMenus.create({
    id: 'analyzeSeo',
    title: 'Analizar SEO de la página',
    contexts: ['page', 'selection', 'link']
  });
});

// Manejar clics en el menú contextual
chrome.contextMenus.onClicked.addListener(function(info, tab) {
  if (info.menuItemId === 'analyzeSeo') {
    // Abrir la extensión en una nueva pestaña o en el popup
    chrome.tabs.create({
      url: chrome.runtime.getURL('popup.html')
    });
  }
});

// Manejar la instalación de la extensión
chrome.runtime.onInstalled.addListener(function(details) {
  if (details.reason === 'install') {
    // Mostrar la página de bienvenida después de la instalación
    chrome.tabs.create({
      url: chrome.runtime.getURL('welcome.html')
    });
  } else if (details.reason === 'update') {
    // Manejar actualizaciones si es necesario
    console.log('Extensión actualizada a la versión ' + chrome.runtime.getManifest().version);
  }
});
