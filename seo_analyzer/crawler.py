import requests
from bs4 import BeautifulSoup
import json
import time
from urllib.parse import urlparse
from config import Config
import re

class SEOAnalyzer:
    def __init__(self, url):
        # Clean up the URL by removing any square brackets
        self.url = re.sub(r'\[(.*?)\]', r'\1', url)
        self.analysis = {
            'url': self.url,
            'page_load_time': None,
            'meta_title': None,
            'meta_description': None,
            'heading_structure': {},
            'image_alt_coverage': 0,
            'internal_links': [],
            'external_links': [],
            'mobile_friendly': False,
            'ssl_enabled': False,
            'status': 'in_progress',
            'error_message': None
        }

    def analyze(self):
        try:
            # Validate URL format
            if not self.url.startswith(('http://', 'https://')):
                self.url = 'https://' + self.url
            start_time = time.time()
            
            # Add headers to mimic a browser request
            headers = {
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            }
            
            response = requests.get(self.url, timeout=Config.TIMEOUT, headers=headers)
            self.analysis['page_load_time'] = time.time() - start_time

            if response.status_code != 200:
                raise Exception(f"HTTP Error: {response.status_code}")

            soup = BeautifulSoup(response.text, 'html.parser')

            # Analyze meta tags
            self._analyze_meta_tags(soup)
            self._analyze_headings(soup)
            self._analyze_images(soup)
            self._analyze_links(soup)
            self._analyze_mobile_friendly()
            self._analyze_ssl()

            self.analysis['status'] = 'completed'
            return self.analysis

        except Exception as e:
            self.analysis['status'] = 'failed'
            self.analysis['error_message'] = str(e)
            return self.analysis

    def _analyze_meta_tags(self, soup):
        self.analysis['meta_title'] = soup.title.string if soup.title else None
        meta_desc = soup.find('meta', attrs={'name': 'description'})
        self.analysis['meta_description'] = meta_desc['content'] if meta_desc else None

    def _analyze_headings(self, soup):
        headings = {'h1': 0, 'h2': 0, 'h3': 0, 'h4': 0, 'h5': 0, 'h6': 0}
        for heading in headings.keys():
            headings[heading] = len(soup.find_all(heading))
        self.analysis['heading_structure'] = headings

    def _analyze_images(self, soup):
        images = soup.find_all('img')
        total_images = len(images)
        alt_count = sum(1 for img in images if img.get('alt'))
        self.analysis['image_alt_coverage'] = (alt_count / total_images * 100) if total_images > 0 else 0

    def _analyze_links(self, soup):
        links = soup.find_all('a', href=True)
        parsed_url = urlparse(self.url)
        domain = f"{parsed_url.scheme}://{parsed_url.netloc}"
        
        for link in links:
            href = link['href']
            if href.startswith(domain):
                self.analysis['internal_links'].append(href)
            else:
                self.analysis['external_links'].append(href)

    def _analyze_mobile_friendly(self):
        # This is a simplified check - in production you'd want to use a more robust method
        self.analysis['mobile_friendly'] = True  # Default to true, implement proper checks

    def _analyze_ssl(self):
        self.analysis['ssl_enabled'] = self.url.startswith('https://')

if __name__ == "__main__":
    import sys
    if len(sys.argv) != 2:
        print("Usage: python crawler.py <url>")
        sys.exit(1)

    url = sys.argv[1]
    analyzer = SEOAnalyzer(url)
    result = analyzer.analyze()
    print(json.dumps(result, indent=2))