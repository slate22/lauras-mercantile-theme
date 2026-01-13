import React from 'react';

export default function SiteFooter(){
  return (
    <footer className="lm-footer">
      <div className="lm-shell">
        <div style={{display:'flex', gap:24, flexWrap:'wrap', alignItems:'flex-start', justifyContent:'space-between'}}>
          <div style={{maxWidth:420}}>
            <div style={{fontFamily:'var(--lm-serif)', fontSize:18, color:'var(--lm-ink)', marginBottom:8}}>Laura’s Mercantile</div>
            <div>Plant-powered wellness from the farm. Sustainably sourced, clearly labeled, third-party tested.</div>
          </div>
          <div style={{display:'flex', gap:16, flexWrap:'wrap'}}>
            <a href="/privacy/">Privacy</a>
            <a href="/terms/">Terms</a>
            <a href="/contact-us/">Contact</a>
          </div>
        </div>
      </div>
    </footer>
  );
}
