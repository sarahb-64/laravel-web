import os
from dotenv import load_dotenv

load_dotenv()

class Config:
    BASE_URL = os.getenv('BASE_URL', 'http://localhost:8000')
    ANALYSIS_ENDPOINT = f"{BASE_URL}/api/seo/analysis"
    TIMEOUT = 30
    TEST_MODE = True  # Add this for testing