import polyglotI18nProvider from 'ra-i18n-polyglot';
// @ts-ignore
import {fr} from '../config/i18n/fr.tsx';

const translations = {
    fr
};

const i18nProvider = polyglotI18nProvider(locale => translations[locale], 'fr');

export default i18nProvider;
