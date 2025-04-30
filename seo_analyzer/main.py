import sys
import json
import requests
from crawler import SEOAnalyzer
from config import Config
import re

def clean_url(url):
    # Remove square brackets and any text inside them
    return re.sub(r'\[(.*?)\]', '', url).strip()

def main():
    if len(sys.argv) != 3:
        print("Usage: python main.py <url> <analysis_id>")
        sys.exit(1)

    # Clean the URL before passing it
    url = clean_url(sys.argv[1])
    analysis_id = sys.argv[2]

    analyzer = SEOAnalyzer(url)
    result = analyzer.analyze()

    # Print results to console in test mode
    if Config.TEST_MODE:
        print(json.dumps(result, indent=2))
    else:
        # Send results back to Laravel
        try:
            response = requests.post(
                f"{Config.ANALYSIS_ENDPOINT}/{analysis_id}",
                json=result
            )
            response.raise_for_status()
            print("Results sent successfully")
        except requests.RequestException as e:
            print(f"Error sending results: {e}")

if __name__ == "__main__":
    main()