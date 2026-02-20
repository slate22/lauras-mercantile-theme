import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import SiteHeader from './components/SiteHeader.jsx';
import SiteFooter from './components/SiteFooter.jsx';
import Home from './pages/Home.jsx';
import ContentPage from './pages/ContentPage.jsx';
import Shop from './pages/Shop.jsx';
import Category from './pages/Category.jsx';
import Search from './pages/Search.jsx';
import LabResultsPage from './pages/LabResultsPage.jsx';

const wooRedirects = ['/cart', '/checkout', '/my-account'];

function HardRedirect({ to }) {
  React.useEffect(() => {
    window.location.assign(to);
  }, [to]);
  return null;
}

export default function App() {
  return (
    <>
      <Routes>
        <Route path="/" element={<Home />} />

        {/* Top nav content */}
        <Route path="/our-approach" element={<ContentPage slug="our-approach" titleFallback="Our Approach" />} />
        <Route path="/lab-results" element={<HardRedirect to="https://laurasmercantile.com/cbd-legal/" />} />
        <Route path="/education" element={<ContentPage slug="education" titleFallback="Education" />} />
        <Route path="/about-laura" element={<ContentPage slug="about-laura" titleFallback="About Laura" />} />
        <Route path="/meet-laura" element={<ContentPage slug="lauras-story-from-lauras-lean-beef-to-full-spectrum-cbd" titleFallback="Laura’s Story" />} />
        <Route path="/lauras-story" element={<ContentPage slug="lauras-story-from-lauras-lean-beef-to-full-spectrum-cbd" titleFallback="Laura’s Story" />} />
        <Route path="/lauras-story-from-lauras-lean-beef-to-full-spectrum-cbd" element={<ContentPage slug="lauras-story-from-lauras-lean-beef-to-full-spectrum-cbd" titleFallback="Laura’s Story" />} />

        {/* Secondary / legacy pages (try by slug) */}
        <Route path="/questions" element={<ContentPage slug="questions" titleFallback="Questions" />} />
        <Route path="/faq" element={<ContentPage slug="faq" titleFallback="FAQ" />} />
        <Route path="/meet-laura" element={<ContentPage slug="meet-laura" titleFallback="Meet Laura" />} />
        <Route path="/military" element={<ContentPage slug="military" titleFallback="Military Program" />} />
        <Route path="/loyalty" element={<ContentPage slug="loyalty" titleFallback="Loyalty Program" />} />

        {/* Shop browsing */}
        <Route path="/shop" element={<Shop />} />
        <Route path="/product-category/:slug" element={<Category />} />

        {/* Simple search */}
        <Route path="/search" element={<Search />} />

        {/* Never hijack Woo critical flows */}
        {wooRedirects.map((p) => (
          <Route key={p} path={p} element={<HardRedirect to={p + '/'} />} />
        ))}
        <Route path="/my-account/*" element={<HardRedirect to="/my-account/" />} />
        <Route path="/checkout/*" element={<HardRedirect to="/checkout/" />} />
        <Route path="/cart/*" element={<HardRedirect to="/cart/" />} />
        <Route path="/outcomes/*" element={<HardRedirect to={window.location.pathname} />} />

        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </>
  );
}
