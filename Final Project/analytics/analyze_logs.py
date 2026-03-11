#!/usr/bin/env python3
import re
from collections import Counter, defaultdict
import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
from datetime import datetime
import argparse
import os

class ApacheLogAnalyzer:
    def __init__(self, access_log_path, error_log_path):
        self.access_log_path = access_log_path
        self.error_log_path = error_log_path
        self.access_data = []
        self.error_data = []
        
        #common Apache log format pattern
        self.access_pattern = r'(\S+) (\S+) (\S+) \[([^]]+)\] "(\S+) (\S+) (\S+)" (\d+) (\d+) "([^"]*)" "([^"]*)"'
        self.error_pattern = r'\[([^]]+)\] \[(\w+):(\w+)\] \[pid (\d+):tid (\d+)\] \[client (\S+):(\d+)\] (.*)'
    
    def parse_access_logs(self):
        """Parse Apache access logs"""
        print("Parsing access logs...")
        
        with open(self.access_log_path, 'r') as f:
            for line in f:
                match = re.match(self.access_pattern, line)
                if match:
                    ip, identity, user, timestamp, method, url, protocol, status, size, referer, user_agent = match.groups()
                    
                    #parse timestamp
                    try:
                        dt = datetime.strptime(timestamp, '%d/%b/%Y:%H:%M:%S %z')
                    except:
                        dt = None
                    
                    self.access_data.append({
                        'ip': ip,
                        'timestamp': dt,
                        'method': method,
                        'url': url,
                        'status': int(status),
                        'size': int(size) if size != '-' else 0,
                        'referer': referer,
                        'user_agent': user_agent
                    })
    
    def parse_error_logs(self):
        """Parse Apache error logs"""
        print("Parsing error logs...")
        
        with open(self.error_log_path, 'r') as f:
            for line in f:
                match = re.match(self.error_pattern, line)
                if match:
                    timestamp, module, level, pid, tid, client, port, message = match.groups()
                    
                    try:
                        dt = datetime.strptime(timestamp, '%a %b %d %H:%M:%S.%f %Y')
                    except:
                        try:
                            dt = datetime.strptime(timestamp, '%a %b %d %H:%M:%S %Y')
                        except:
                            dt = None
                    
                    self.error_data.append({
                        'timestamp': dt,
                        'level': level,
                        'client': client,
                        'message': message
                    })
    
    def generate_access_statistics(self):
        """Generate access log statistics"""
        if not self.access_data:
            return {}
            
        df = pd.DataFrame(self.access_data)
        
        stats = {
            'page_access_counts': df['url'].value_counts().to_dict(),
            'ip_access_counts': df['ip'].value_counts().to_dict(),
            'browser_counts': self._extract_browsers(df),
            'status_code_counts': df['status'].value_counts().to_dict(),
            'hourly_activity': self._get_hourly_activity(df),
            'daily_activity': self._get_daily_activity(df)
        }
        
        return stats
    
    def generate_error_statistics(self):
        """Generate error log statistics"""
        if not self.error_data:
            return {}
            
        df = pd.DataFrame(self.error_data)
        
        stats = {
            'error_level_counts': df['level'].value_counts().to_dict(),
            'client_error_counts': df['client'].value_counts().to_dict(),
            'hourly_errors': self._get_hourly_errors(df),
            'daily_errors': self._get_daily_errors(df)
        }
        
        return stats
    
    def _extract_browsers(self, df):
        """Extract browser information from user agent"""
        browsers = []
        for ua in df['user_agent']:
            ua_lower = ua.lower()
            if 'chrome' in ua_lower:
                browsers.append('Chrome')
            elif 'firefox' in ua_lower:
                browsers.append('Firefox')
            elif 'safari' in ua_lower and 'chrome' not in ua_lower:
                browsers.append('Safari')
            elif 'edge' in ua_lower:
                browsers.append('Edge')
            elif 'opera' in ua_lower:
                browsers.append('Opera')
            else:
                browsers.append('Other')
        
        return Counter(browsers)
    
    def _get_hourly_activity(self, df):
        """Get hourly access activity"""
        df_with_hour = df.copy()
        df_with_hour['hour'] = df_with_hour['timestamp'].dt.hour
        return df_with_hour['hour'].value_counts().sort_index().to_dict()
    
    def _get_daily_activity(self, df):
        """Get daily access activity"""
        df_with_date = df.copy()
        df_with_date['date'] = df_with_date['timestamp'].dt.date
        return df_with_date['date'].value_counts().sort_index().to_dict()
    
    def _get_hourly_errors(self, df):
        """Get hourly error activity"""
        df_with_hour = df.copy()
        df_with_hour['hour'] = df_with_hour['timestamp'].dt.hour
        return df_with_hour['hour'].value_counts().sort_index().to_dict()
    
    def _get_daily_errors(self, df):
        """Get daily error activity"""
        df_with_date = df.copy()
        df_with_date['date'] = df_with_date['timestamp'].dt.date
        return df_with_date['date'].value_counts().sort_index().to_dict()
    
    def create_diagrams(self, access_stats, error_stats):
        """Create timeline diagrams and charts"""
        plt.style.use('seaborn-v0_8')
        
        #create output directory
        os.makedirs('output/diagrams', exist_ok=True)
        
        #aaccess Log Diagrams
        self._create_access_diagrams(access_stats)
        
        #error Log Diagrams
        self._create_error_diagrams(error_stats)
        
        plt.close('all')
    
    def _create_access_diagrams(self, stats):
        """Create access log diagrams"""
        #page access timeline
        if stats.get('daily_activity'):
            plt.figure(figsize=(12, 6))
            dates = list(stats['daily_activity'].keys())
            counts = list(stats['daily_activity'].values())
            
            plt.plot(dates, counts, marker='o', linewidth=2, markersize=4)
            plt.title('Page Access Timeline - Daily Activity')
            plt.xlabel('Date')
            plt.ylabel('Number of Accesses')
            plt.xticks(rotation=45)
            plt.tight_layout()
            plt.savefig('output/diagrams/access_timeline_daily.png', dpi=300, bbox_inches='tight')
        
        #hourly activity
        if stats.get('hourly_activity'):
            plt.figure(figsize=(10, 6))
            hours = list(stats['hourly_activity'].keys())
            counts = list(stats['hourly_activity'].values())
            
            plt.bar(hours, counts)
            plt.title('Page Access Timeline - Hourly Activity')
            plt.xlabel('Hour of Day')
            plt.ylabel('Number of Accesses')
            plt.tight_layout()
            plt.savefig('output/diagrams/access_timeline_hourly.png', dpi=300, bbox_inches='tight')
        
        # Top pages
        if stats.get('page_access_counts'):
            plt.figure(figsize=(10, 6))
            pages = list(stats['page_access_counts'].keys())[:10]
            counts = list(stats['page_access_counts'].values())[:10]
            
            plt.bar(range(len(pages)), counts)
            plt.title('Top 10 Most Accessed Pages')
            plt.xlabel('Pages')
            plt.ylabel('Access Count')
            plt.xticks(range(len(pages)), [p[:30] + '...' if len(p) > 30 else p for p in pages], rotation=45)
            plt.tight_layout()
            plt.savefig('output/diagrams/top_pages.png', dpi=300, bbox_inches='tight')
        
        #bbrowser distribution
        if stats.get('browser_counts'):
            plt.figure(figsize=(8, 8))
            browsers = list(stats['browser_counts'].keys())
            counts = list(stats['browser_counts'].values())
            
            plt.pie(counts, labels=browsers, autopct='%1.1f%%', startangle=90)
            plt.title('Browser Distribution')
            plt.savefig('output/diagrams/browser_distribution.png', dpi=300, bbox_inches='tight')
    
    def _create_error_diagrams(self, stats):
        """Create error log diagrams"""
        #error timeline
        if stats.get('daily_errors'):
            plt.figure(figsize=(12, 6))
            dates = list(stats['daily_errors'].keys())
            counts = list(stats['daily_errors'].values())
            
            plt.plot(dates, counts, marker='o', color='red', linewidth=2, markersize=4)
            plt.title('Error Timeline - Daily Errors')
            plt.xlabel('Date')
            plt.ylabel('Number of Errors')
            plt.xticks(rotation=45)
            plt.tight_layout()
            plt.savefig('output/diagrams/error_timeline_daily.png', dpi=300, bbox_inches='tight')
        
        #hourly errors
        if stats.get('hourly_errors'):
            plt.figure(figsize=(10, 6))
            hours = list(stats['hourly_errors'].keys())
            counts = list(stats['hourly_errors'].values())
            
            plt.bar(hours, counts, color='red')
            plt.title('Error Timeline - Hourly Distribution')
            plt.xlabel('Hour of Day')
            plt.ylabel('Number of Errors')
            plt.tight_layout()
            plt.savefig('output/diagrams/error_timeline_hourly.png', dpi=300, bbox_inches='tight')
        
        #error levels
        if stats.get('error_level_counts'):
            plt.figure(figsize=(8, 6))
            levels = list(stats['error_level_counts'].keys())
            counts = list(stats['error_level_counts'].values())
            
            plt.bar(levels, counts, color=['red', 'orange', 'yellow'])
            plt.title('Error Level Distribution')
            plt.xlabel('Error Level')
            plt.ylabel('Count')
            plt.tight_layout()
            plt.savefig('output/diagrams/error_levels.png', dpi=300, bbox_inches='tight')

def generate_sample_logs():
    """Generate sample logs if real logs are insufficient"""
    print("Generating sample log data...")
    
    #sample access logs
    sample_access_entries = [
        '192.168.1.100 - - [15/Nov/2024:10:30:45 +0000] "GET /index.html HTTP/1.1" 200 1234 "https://example.com" "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"',
        '192.168.1.101 - - [15/Nov/2024:10:31:22 +0000] "GET /about.html HTTP/1.1" 200 2345 "https://example.com" "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15"',
        '192.168.1.102 - - [15/Nov/2024:11:15:33 +0000] "GET /contact.html HTTP/1.1" 404 123 "-" "Mozilla/5.0 (X11; Linux x86_64; rv:78.0) Gecko/20100101 Firefox/78.0"',
        '192.168.1.100 - - [15/Nov/2024:14:22:11 +0000] "POST /submit-form HTTP/1.1" 302 456 "https://example.com/contact.html" "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"',
        '192.168.1.103 - - [16/Nov/2024:09:05:44 +0000] "GET /products.html HTTP/1.1" 200 3456 "https://example.com" "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Edge/91.0.864.59"'
    ]
    
    #sample error logs
    sample_error_entries = [
        '[Sat Nov 16 10:30:45.123456 2024] [authz_core:error] [pid 1234:tid 140001] [client 192.168.1.102:54321] AH01630: client is not authorized to access this directory',
        '[Sat Nov 16 11:15:33.654321 2024] [core:error] [pid 1234:tid 140002] [client 192.168.1.101:54322] File does not exist: /var/www/html/contact.html',
        '[Sat Nov 16 14:22:11.987654 2024] [ssl:error] [pid 1235:tid 140003] [client 192.168.1.100:54323] AH02032: Hostname example.com provided via SNI and hostname 192.168.1.50 provided via HTTP are different'
    ]
    
    #write sample access logs
    with open('apache/access.log', 'w') as f:
        for entry in sample_access_entries:
            f.write(entry + '\n')
    
    #write sample error logs
    with open('apache/error.log', 'w') as f:
        for entry in sample_error_entries:
            f.write(entry + '\n')
    
    print("Sample logs generated!")

def main():
    parser = argparse.ArgumentParser(description='Apache Log Analyzer')
    parser.add_argument('--access-log', default='apache/access.log', help='Path to access log file')
    parser.add_argument('--error-log', default='apache/error.log', help='Path to error log file')
    parser.add_argument('--generate-samples', action='store_true', help='Generate sample log data')
    
    args = parser.parse_args()
    
    #create directories if they don't exist
    os.makedirs('apache', exist_ok=True)
    os.makedirs('output/diagrams', exist_ok=True)
    
    if args.generate_samples:
        generate_sample_logs()
        return
    
    #check if log files exist and have content
    if not os.path.exists(args.access_log) or os.path.getsize(args.access_log) == 0:
        print("No access log found or empty. Generating sample data...")
        generate_sample_logs()
    
    #initialize analyzer
    analyzer = ApacheLogAnalyzer(args.access_log, args.error_log)
    
    #parse logs
    analyzer.parse_access_logs()
    analyzer.parse_error_logs()
    
    #generate statistics
    access_stats = analyzer.generate_access_statistics()
    error_stats = analyzer.generate_error_statistics()
    
    #create diagrams
    analyzer.create_diagrams(access_stats, error_stats)
    
    #generate PDF report
    generate_pdf_report(access_stats, error_stats)
    
    print("Analysis complete! Check the 'output' directory for results.")

def generate_pdf_report(access_stats, error_stats):
    """Generate a PDF report with statistics"""
    from fpdf import FPDF
    
    pdf = FPDF()
    pdf.set_auto_page_break(auto=True, margin=15)
    pdf.add_page()
    
    #title
    pdf.set_font('Arial', 'B', 16)
    pdf.cell(0, 10, 'Web Log Evaluation Report', 0, 1, 'C')
    pdf.ln(10)
    
    #sccess Statistics
    pdf.set_font('Arial', 'B', 14)
    pdf.cell(0, 10, 'Access Log Statistics', 0, 1)
    pdf.set_font('Arial', '', 12)
    
    if access_stats:
        pdf.cell(0, 10, f"Total Access Entries: {sum(access_stats.get('page_access_counts', {}).values())}", 0, 1)
        pdf.cell(0, 10, f"Unique Pages: {len(access_stats.get('page_access_counts', {}))}", 0, 1)
        pdf.cell(0, 10, f"Unique IPs: {len(access_stats.get('ip_access_counts', {}))}", 0, 1)
        pdf.ln(5)
        
        #top pages
        pdf.set_font('Arial', 'B', 12)
        pdf.cell(0, 10, 'Top 5 Most Accessed Pages:', 0, 1)
        pdf.set_font('Arial', '', 10)
        for page, count in list(access_stats.get('page_access_counts', {}).items())[:5]:
            pdf.cell(0, 8, f"  {page}: {count} accesses", 0, 1)
        
        pdf.ln(5)
        
        #browser distribution
        pdf.set_font('Arial', 'B', 12)
        pdf.cell(0, 10, 'Browser Distribution:', 0, 1)
        pdf.set_font('Arial', '', 10)
        for browser, count in access_stats.get('browser_counts', {}).items():
            pdf.cell(0, 8, f"  {browser}: {count} requests", 0, 1)
    
    pdf.add_page()
    
    #error Statistics
    pdf.set_font('Arial', 'B', 14)
    pdf.cell(0, 10, 'Error Log Statistics', 0, 1)
    pdf.set_font('Arial', '', 12)
    
    if error_stats:
        pdf.cell(0, 10, f"Total Error Entries: {sum(error_stats.get('error_level_counts', {}).values())}", 0, 1)
        pdf.cell(0, 10, f"Unique Clients with Errors: {len(error_stats.get('client_error_counts', {}))}", 0, 1)
        pdf.ln(5)
        
        #error levels
        pdf.set_font('Arial', 'B', 12)
        pdf.cell(0, 10, 'Error Level Distribution:', 0, 1)
        pdf.set_font('Arial', '', 10)
        for level, count in error_stats.get('error_level_counts', {}).items():
            pdf.cell(0, 8, f"  {level}: {count} errors", 0, 1)
    
    #add diagrams to PDF
    pdf.add_page()
    pdf.set_font('Arial', 'B', 14)
    pdf.cell(0, 10, 'Timeline Diagrams', 0, 1)
    pdf.set_font('Arial', '', 10)
    pdf.cell(0, 10, 'Please refer to the following diagrams in the output/diagrams folder:', 0, 1)
    pdf.cell(0, 8, '- access_timeline_daily.png: Daily page access timeline', 0, 1)
    pdf.cell(0, 8, '- access_timeline_hourly.png: Hourly access distribution', 0, 1)
    pdf.cell(0, 8, '- error_timeline_daily.png: Daily error timeline', 0, 1)
    pdf.cell(0, 8, '- error_timeline_hourly.png: Hourly error distribution', 0, 1)
    pdf.cell(0, 8, '- top_pages.png: Top accessed pages', 0, 1)
    pdf.cell(0, 8, '- browser_distribution.png: Browser usage pie chart', 0, 1)
    pdf.cell(0, 8, '- error_levels.png: Error level distribution', 0, 1)
    
    pdf.output('output/statistics.pdf')
    print("PDF report generated: output/statistics.pdf")

if __name__ == '__main__':
    main()