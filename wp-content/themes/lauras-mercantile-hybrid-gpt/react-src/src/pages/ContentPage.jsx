import React from 'react';

export default function ContentPage({ slug, titleFallback }){
  const [state, setState] = React.useState({ loading:true, error:null, page:null });

  React.useEffect(() => {
    let cancelled = false;
    async function run(){
      try{
        const restUrl = window.__LM__?.restUrl || '/wp-json/';
        const url = `${restUrl}wp/v2/pages?slug=${encodeURIComponent(slug)}&_fields=title,content,slug`;
        const res = await fetch(url, { credentials:'same-origin' });
        if(!res.ok) throw new Error(`WP REST error ${res.status}`);
        const data = await res.json();
        const page = data?.[0] ?? null;
        if(!cancelled) setState({ loading:false, error:null, page });
      }catch(e){
        if(!cancelled) setState({ loading:false, error:e.message, page:null });
      }
    }
    run();
    return () => { cancelled = true; };
  }, [slug]);

  return (
    <div className="lm-page">
      <div className="lm-shell lm-content">
        {state.loading ? <div className="lm-notice">Loading…</div> : null}
        {state.error ? <div className="lm-notice">Could not load this page from WordPress. ({state.error})</div> : null}

        {state.page ? (
          <div className="wp-content">
            <h1 dangerouslySetInnerHTML={{ __html: state.page.title?.rendered || titleFallback || '' }} />
            <div dangerouslySetInnerHTML={{ __html: state.page.content?.rendered || '' }} />
          </div>
        ) : (!state.loading ? (
          <div className="wp-content">
            <h1>{titleFallback || 'Page'}</h1>
            <p>This route is wired in React. To display real content here, create a WordPress page with slug <strong>{slug}</strong>.</p>
          </div>
        ) : null)}
      </div>
    </div>
  );
}
