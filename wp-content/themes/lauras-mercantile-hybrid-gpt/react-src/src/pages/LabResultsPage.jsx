import React from 'react';

const LabResultsPage = () => {
    const [lotNumber, setLotNumber] = React.useState('');

    const handleSearch = (e) => {
        e.preventDefault();
        if (lotNumber.trim()) {
            // Redirect to the WordPress search page as per the live site's logic
            // name="s" and hidden id="181"
            window.location.href = `/?s=${encodeURIComponent(lotNumber)}&id=181`;
        }
    };

    return (
        <div className="lm-page lab-results-page">
            <div className="lm-shell">
                <header className="page-header">
                    <h1>Lab Results</h1>
                    <div className="header-divider"></div>
                </header>

                <section className="coa-search-section">
                    <div className="content-card">
                        <h2>Find Your CBD Lab Results (COA)</h2>
                        <p>Search the lot number found on your bottle or package below to see the relevant lab results.</p>

                        <form onSubmit={handleSearch} className="coa-search-form">
                            <input
                                type="text"
                                value={lotNumber}
                                onChange={(e) => setLotNumber(e.target.value)}
                                placeholder="Enter lot number (e.g., LMT42127)"
                                className="coa-input"
                            />
                            <button type="submit" className="coa-button">Search</button>
                        </form>
                    </div>
                </section>

                <section className="coa-instructions-section">
                    <h2>How to Read a COA</h2>
                    <div className="instructions-grid">
                        <div className="instruction-item">
                            <h3>Extract COA</h3>
                            <img
                                src="https://cdn.shopify.com/s/files/1/1163/1342/files/how_to_read_a_COA_-_extract_fixed_1024x1024.PNG?v=1544543664"
                                alt="How to read an extract COA"
                                className="instruction-img"
                            />
                        </div>
                        <div className="instruction-item">
                            <h3>CBD Products COA</h3>
                            <img
                                src="https://cdn.shopify.com/s/files/1/1163/1342/files/how_to_read_a_COA_1024x1024.PNG?v=1544546702"
                                alt="How to read a CBD products COA"
                                className="instruction-img"
                            />
                        </div>
                    </div>
                </section>

                <section className="explanations-section">
                    <div className="explanation-block">
                        <h2>Further Explanations</h2>
                        <h3>Laura’s Hemp Chocolates</h3>
                        <p>
                            Laura’s Hemp Chocolates contain 0.00% THC. Because hemp seeds do not contain CBD or THC,
                            hermp seeds are "THC free" within the limits of detection.
                        </p>

                        <h3>Homestead Alternatives CBD</h3>
                        <p>
                            Homestead Alternatives CBD products are Full Spectrum and naturally contain trace amounts of THC.
                            By law, there may be no more than 0.3% THC in any CBD product.
                        </p>
                        <p>
                            We third-party lab test every batch of our product. A typical result for our product is 0.13% THC.
                        </p>

                        <div className="sample-result">
                            <img
                                src="https://cdn.shopify.com/s/files/1/1163/1342/files/LMT42127_1024x1024.PNG?v=1544050731"
                                alt="Sample Lab Result"
                                className="sample-img"
                            />
                        </div>
                    </div>

                    <div className="explanation-block secondary">
                        <h2>Why Don’t We Remove 100% of the THC?</h2>
                        <p>
                            Because our commitment is to stay as close to the plant as possible. We do not use isolates
                            in our products because we believe in the "entourage effect"—the idea that CBD is more effective
                            when it works in concert with the other cannabinoids and terpenes naturally found in the hemp plant.
                        </p>
                        <div className="thc-warning">
                            <p><strong>Note:</strong> We recommend that anyone who is subject to drug screening for any reason NOT use any Full Spectrum CBD products.</p>
                        </div>
                    </div>
                </section>

                <section className="herbal-coas-section">
                    <h2>Herbal Certifications</h2>
                    <div className="herbal-grid">
                        <a href="https://laurasmercantile.com/lha20221hydc-click-here/" className="herbal-card" target="_blank" rel="noopener noreferrer">
                            <span className="herbal-name">Goldenseal</span>
                            <span className="herbal-action">View COA →</span>
                        </a>
                        <a href="https://laurasmercantile.com/lha20221travclick-here/" className="herbal-card" target="_blank" rel="noopener noreferrer">
                            <span className="herbal-name">Turkey Tail</span>
                            <span className="herbal-action">View COA →</span>
                        </a>
                        <a href="https://laurasmercantile.com/lha20221ulmrclick-here/" className="herbal-card" target="_blank" rel="noopener noreferrer">
                            <span className="herbal-name">Slippery Elm</span>
                            <span className="herbal-action">View COA →</span>
                        </a>
                    </div>
                </section>

                <section className="lot-number-guide">
                    <h2>Where to Find Your Lot Number</h2>
                    <div className="lot-guide-content">
                        <ul>
                            <li><strong>CBD Oil Bottles:</strong> Look on the bottom of the bottle.</li>
                            <li><strong>Caramels & Chocolates:</strong> Look on the back of the package.</li>
                            <li><strong>Softgels & Capsules:</strong> Look on the side or bottom of the bottle.</li>
                            <li><strong>Product Hang Tags:</strong> Look for a stamped or printed code.</li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    );
};

export default LabResultsPage;
