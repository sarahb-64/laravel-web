document.addEventListener('DOMContentLoaded', function() {
  // Elementos del DOM
  const apiKeyInput = document.getElementById('apiKey');
  const saveApiKeyBtn = document.getElementById('saveApiKey');
  const analyzeBtn = document.getElementById('analyzeBtn');
  const authSection = document.getElementById('authSection');
  const analysisSection = document.getElementById('analysisSection');
  const currentUrlElement = document.getElementById('currentUrl');
  const metaTagsContainer = document.getElementById('metaTags');
  const loadingElement = document.getElementById('loading');
  const errorElement = document.getElementById('error');
  
  // URL base de la API
  const API_BASE_URL = 'http://localhost:8000'; // Ajusta según tu configuración
  
  // Verificar si ya hay una API key guardada
  chrome.storage.local.get(['apiKey'], function(result) {
    if (result.apiKey) {
      // Si ya hay una API key guardada, mostramos la sección de análisis
      showAnalysisSection();
    } else {
      // Si no hay API key, mostramos el formulario para ingresarla
      authSection.style.display = 'block';
    }
  });
  
  // Manejador para guardar la API key
  saveApiKeyBtn.addEventListener('click', function() {
    const apiKey = apiKeyInput.value.trim();
    
    if (!apiKey) {
      showError('Por favor ingresa una API key válida');
      return;
    }
    
    // Guardar la API key en el almacenamiento local de Chrome
    chrome.storage.local.set({ apiKey: apiKey }, function() {
      console.log('API Key guardada');
      showAnalysisSection();
    });
  });
  
  // Manejador para el botón de análisis
  analyzeBtn.addEventListener('click', analyzeCurrentPage);
  
  // Función para mostrar la sección de análisis
  function showAnalysisSection() {
    authSection.style.display = 'none';
    analysisSection.style.display = 'block';
    
    // Obtener la URL actual de la pestaña activa
    chrome.tabs.query({active: true, currentWindow: true}, function(tabs) {
      const currentUrl = tabs[0].url;
      currentUrlElement.textContent = currentUrl;
      currentUrlElement.className = 'current-url';
    });
  }
  
  // Función para analizar la página actual
  async function analyzeCurrentPage() {
    // Mostrar indicador de carga
    loadingElement.style.display = 'block';
    metaTagsContainer.innerHTML = '';
    hideError();
    
    try {
      // Obtener la URL actual
      const tabs = await chrome.tabs.query({active: true, currentWindow: true});
      const currentUrl = tabs[0].url;
      
      // Obtener la API key
      const result = await chrome.storage.local.get(['apiKey']);
      const apiKey = result.apiKey;
      
      if (!apiKey) {
        throw new Error('No se encontró la API key. Por favor, recarga la extensión e ingrésala de nuevo.');
      }
      
      // Realizar la petición a la API
      const response = await fetch(`${API_BASE_URL}/api/seo/analyze-meta-tags`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${apiKey}`,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          url: currentUrl
        })
      });
      
      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Error al analizar la página');
      }
      
      const data = await response.json();
      displayMetaTags(data);
      
    } catch (error) {
      console.error('Error al analizar la página:', error);
      showError(error.message || 'Ocurrió un error al analizar la página. Por favor, inténtalo de nuevo.');
    } finally {
      loadingElement.style.display = 'none';
    }
  }
  
  // Función para mostrar los meta tags en la interfaz
  function displayMetaTags(data) {
    metaTagsContainer.innerHTML = '';
    
    if (!data || Object.keys(data).length === 0) {
      metaTagsContainer.innerHTML = '<p>No se encontraron meta tags en esta página.</p>';
      return;
    }
    
    // Mostrar título
    if (data.title) {
      const titleElement = createMetaTagElement('Título', data.title, data.title_suggested);
      metaTagsContainer.appendChild(titleElement);
    }
    
    // Mostrar meta descripción
    if (data.meta_description) {
      const descElement = createMetaTagElement(
        'Meta Descripción', 
        data.meta_description, 
        data.meta_description_suggested
      );
      metaTagsContainer.appendChild(descElement);
    }
    
    // Mostrar meta keywords si están disponibles
    if (data.meta_keywords) {
      const keywordsElement = createMetaTagElement(
        'Palabras Clave', 
        data.meta_keywords, 
        data.meta_keywords_suggested
      );
      metaTagsContainer.appendChild(keywordsElement);
    }
    
    // Mostrar encabezados h1 si están disponibles
    if (data.headings && data.headings.h1 && data.headings.h1.length > 0) {
      const h1Element = document.createElement('div');
      h1Element.className = 'meta-tag';
      h1Element.innerHTML = `
        <h3>Encabezados H1</h3>
        <p>Se encontraron ${data.headings.h1.length} etiquetas H1 en la página.</p>
        <p><strong>Primer H1:</strong> <span class="value">${data.headings.h1[0]}</span></p>
      `;
      metaTagsContainer.appendChild(h1Element);
    }
    
    // Mostrar sugerencias generales si están disponibles
    if (data.suggestions && data.suggestions.length > 0) {
      const suggestionsElement = document.createElement('div');
      suggestionsElement.className = 'meta-tag';
      
      let suggestionsHTML = '<h3>Sugerencias de Mejora</h3><ul>';
      data.suggestions.forEach(suggestion => {
        suggestionsHTML += `<li>${suggestion}</li>`;
      });
      suggestionsHTML += '</ul>';
      
      suggestionsElement.innerHTML = suggestionsHTML;
      metaTagsContainer.appendChild(suggestionsElement);
    }
  }
  
  // Función para crear un elemento de meta tag
  function createMetaTagElement(title, currentValue, suggestedValue) {
    const element = document.createElement('div');
    element.className = 'meta-tag';
    
    let html = `<h3>${title}</h3>`;
    
    if (currentValue) {
      html += `<p><strong>Actual:</strong> <span class="value">${currentValue}</span></p>`;
    }
    
    if (suggestedValue) {
      html += `<p><strong>Sugerido:</strong> <span class="value">${suggestedValue}</span></p>`;
    }
    
    element.innerHTML = html;
    return element;
  }
  
  // Funciones auxiliares para mostrar/ocultar errores
  function showError(message) {
    errorElement.textContent = message;
    errorElement.style.display = 'block';
    
    // Ocultar el mensaje después de 5 segundos
    setTimeout(hideError, 5000);
  }
  
  function hideError() {
    errorElement.style.display = 'none';
  }
});
