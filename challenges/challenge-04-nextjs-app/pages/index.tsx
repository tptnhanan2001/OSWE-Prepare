import { useState } from 'react';

export default function Home() {
  const [url, setUrl] = useState('');
  const [result, setResult] = useState<any>(null);

  const handleFetch = async () => {
    const response = await fetch('/api/fetch-url', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ url })
    });
    const data = await response.json();
    setResult(data);
  };

  return (
    <div style={{ padding: '20px', maxWidth: '800px', margin: '0 auto' }}>
      <h1>Social Media App</h1>
      <div>
        <h2>Fetch URL (SSRF Test)</h2>
        <input
          type="text"
          value={url}
          onChange={(e) => setUrl(e.target.value)}
          placeholder="Enter URL"
          style={{ width: '400px', padding: '8px' }}
        />
        <button onClick={handleFetch} style={{ padding: '8px 16px', marginLeft: '10px' }}>
          Fetch
        </button>
        {result && (
          <pre style={{ background: '#f0f0f0', padding: '10px', marginTop: '10px' }}>
            {JSON.stringify(result, null, 2)}
          </pre>
        )}
      </div>
    </div>
  );
}

