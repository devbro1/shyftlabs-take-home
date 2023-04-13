import React from 'react';
import { Link } from 'react-router-dom';
import { __FooterStyles as Styles } from './footer.styles';

// footer component of dashboard base layout.
const FooterComp: React.FC = () => {
    return (
        <footer className={Styles.root}>
            <div className={Styles.firstRow}>
                <div className={Styles.firstRowItem}>
                    <span className={Styles.firstRowTitle}>Devbro Software</span>
                    <a title="Tailwind CSS Dropdowns" href="/" className={Styles.firstRowDesc}>
                        Laravel + React + Tailwind
                    </a>
                </div>
                <div className={Styles.firstRowItem}>
                    <span className={Styles.firstRowTitle}>Components</span>
                    <a title="Tailwind CSS Dropdowns" href="/" className={Styles.firstRowDesc}>
                        Tailwind Dropdowns
                    </a>
                </div>
                <div className={Styles.firstRowItem}>
                    <span className={Styles.firstRowTitle}>Utilities</span>
                    <a title="Tailwind Cheatsheet" href="/" className={Styles.firstRowDesc}>
                        Cheatsheet
                    </a>
                </div>
            </div>
            <div className={Styles.lastRow}>
                <div className={Styles.lastRowContainer}>
                    © {new Date().getFullYear()} Devbro<span className={Styles.lastRowItem}>-</span>
                    <Link title="" to="/">
                        Privacy
                    </Link>
                    <span className={Styles.lastRowItem}>-</span>
                    <Link title="" to="/">
                        Legal
                    </Link>
                    <span className={Styles.lastRowItem}>-</span>
                    <Link title="" to="/">
                        Cookies
                    </Link>
                    <span className={Styles.lastRowItem}>-</span>
                    Author: <a href="mailto:farzadk@gmail.com">Farzad Khalafi</a>
                </div>
            </div>
        </footer>
    );
};

export default FooterComp;
