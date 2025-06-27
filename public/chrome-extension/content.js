// Escuchar mensajes del background script o popup
chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {
  if (request.action === 'extractMetaTags') {
    // Extraer los meta tags de la página
    const metaTags = extractMetaTags();
    
    // Enviar la respuesta de vuelta
    sendResponse({
      success: true,
      data: metaTags
    });
    
    // Devolver true para indicar que usaremos sendResponse de forma asíncrona
    return true;
  }
});

// Función para extraer los meta tags de la página
function extractMetaTags() {
  const result = {
    title: document.title || '',
    meta_description: '',
    meta_keywords: '',
    meta_robots: '',
    og_tags: {},
    twitter_tags: {},
    headings: {
      h1: [],
      h2: [],
      h3: []
    },
    links: {
      internal: [],
      external: []
    },
    images: []
  };
  
  // Extraer meta description
  const metaDescription = document.querySelector('meta[name="description"]');
  if (metaDescription) {
    result.meta_description = metaDescription.getAttribute('content') || '';
  }
  
  // Extraer meta keywords
  const metaKeywords = document.querySelector('meta[name="keywords"]');
  if (metaKeywords) {
    result.meta_keywords = metaKeywords.getAttribute('content') || '';
  }
  
  // Extraer meta robots
  const metaRobots = document.querySelector('meta[name="robots"]');
  if (metaRobots) {
    result.meta_robots = metaRobots.getAttribute('content') || '';
  }
  
  // Extraer Open Graph tags
  document.querySelectorAll('meta[property^="og:"]').forEach(tag => {
    const property = tag.getAttribute('property').replace('og:', '');
    result.og_tags[property] = tag.getAttribute('content') || '';
  });
  
  // Extraer Twitter Card tags
  document.querySelectorAll('meta[name^="twitter:"]').forEach(tag => {
    const name = tag.getAttribute('name').replace('twitter:', '');
    result.twitter_tags[name] = tag.getAttribute('content') || '';
  });
  
  // Extraer encabezados
  result.headings.h1 = Array.from(document.querySelectorAll('h1')).map(h1 => h1.textContent.trim());
  result.headings.h2 = Array.from(document.querySelectorAll('h2')).map(h2 => h2.textContent.trim());
  result.headings.h3 = Array.from(document.querySelectorAll('h3')).map(h3 => h3.textContent.trim());
  
  // Analizar enlaces
  const currentHost = window.location.hostname;
  document.querySelectorAll('a').forEach(link => {
    const href = link.getAttribute('href');
    if (!href) return;
    
    try {
      const url = new URL(href, window.location.href);
      if (url.hostname === currentHost || !url.hostname) {
        result.links.internal.push({
          url: href,
          text: link.textContent.trim(),
          title: link.getAttribute('title') || '',
          nofollow: link.rel.includes('nofollow')
        });
      } else {
        result.links.external.push({
          url: href,
          text: link.textContent.trim(),
          title: link.getAttribute('title') || '',
          nofollow: link.rel.includes('nofollow')
        });
      }
    } catch (e) {
      console.error('Error al analizar enlace:', e);
    }
  });
  
  // Analizar imágenes
  document.querySelectorAll('img').forEach(img => {
    const src = img.getAttribute('src');
    if (src) {
      result.images.push({
        src: src,
        alt: img.getAttribute('alt') || '',
        title: img.getAttribute('title') || '',
        width: img.width || null,
        height: img.height || null
      });
    }
  });
  
  // Calcular estadísticas
  result.stats = {
    word_count: document.body.textContent.trim().split(/\s+/).length,
    title_length: result.title.length,
    description_length: result.meta_description.length,
    h1_count: result.headings.h1.length,
    h2_count: result.headings.h2.length,
    h3_count: result.headings.h3.length,
    image_count: result.images.length,
    internal_links_count: result.links.internal.length,
    external_links_count: result.links.external.length
  };
  
  // Generar sugerencias
  result.suggestions = generateSuggestions(result);
  
  return result;
}

// Función para generar sugerencias de mejora
function generateSuggestions(data) {
  const suggestions = [];
  
  // Verificar título
  if (!data.title) {
    suggestions.push('La página no tiene un título definido. Añade un título único y descriptivo.');
  } else if (data.title.length < 30) {
    suggestions.push(`El título es demasiado corto (${data.title.length} caracteres). Intenta que tenga entre 30 y 60 caracteres.`);
  } else if (data.title.length > 60) {
    suggestions.push(`El título es demasiado largo (${data.title.length} caracteres). Intenta que tenga menos de 60 caracteres.`);
  }
  
  // Verificar meta descripción
  if (!data.meta_description) {
    suggestions.push('La página no tiene una meta descripción. Añade una descripción atractiva que resuma el contenido.');
  } else if (data.meta_description.length < 120) {
    suggestions.push(`La meta descripción es corta (${data.meta_description.length} caracteres). Intenta que tenga entre 120 y 160 caracteres.`);
  } else if (data.meta_description.length > 160) {
    suggestions.push(`La meta descripción es demasiado larga (${data.meta_description.length} caracteres). Intenta que tenga menos de 160 caracteres.`);
  }
  
  // Verificar encabezados H1
  if (data.headings.h1.length === 0) {
    suggestions.push('No se encontró ningún encabezado H1. Asegúrate de tener exactamente un H1 por página.');
  } else if (data.headings.h1.length > 1) {
    suggestions.push(`Se encontraron múltiples encabezados H1 (${data.headings.h1.length}). Intenta tener solo un H1 por página.`);
  }
  
  // Verificar imágenes sin texto alternativo
  const imagesWithoutAlt = data.images.filter(img => !img.alt.trim());
  if (imagesWithoutAlt.length > 0) {
    suggestions.push(`Se encontraron ${imagesWithoutAlt.length} imágenes sin texto alternativo (atributo alt). Añade textos alternativos descriptivos.`);
  }
  
  // Verificar enlaces
  if (data.links.internal.length === 0) {
    suggestions.push('No se encontraron enlaces internos. Añade enlaces a otras páginas de tu sitio para mejorar la navegación y el SEO.');
  }
  
  if (data.links.external.length === 0) {
    suggestions.push('No se encontraron enlaces externos. Considera añadir referencias a fuentes externas relevantes.');
  }
  
  // Verificar longitud del contenido
  if (data.stats.word_count < 300) {
    suggestions.push(`El contenido es bastante corto (${data.stats.word_count} palabras). Intenta crear contenido más detallado y valioso.`);
  }
  
  return suggestions;
}

// Inyectar estilos para resaltar elementos analizados
function injectStyles() {
  const style = document.createElement('style');
  style.textContent = `
    .seo-analyzer-highlight {
      outline: 2px solid #4a6cf7 !important;
      position: relative;
      z-index: 2147483647 !important;
    }
    .seo-analyzer-tooltip {
      position: absolute;
      background: #4a6cf7;
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      font-family: Arial, sans-serif;
      white-space: nowrap;
      z-index: 2147483647;
      pointer-events: none;
    }
  `;
  document.head.appendChild(style);
}

// Inicializar la inyección de estilos
injectStyles();
