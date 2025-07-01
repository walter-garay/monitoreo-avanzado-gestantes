// Tipo para los posibles tipos de formato
type FormatType = 'default' | 'long' | 'medium' | 'short' | 'withTime';

export const formatDate = (date: string | undefined, formatType: FormatType = 'default'): string => {
    if (!date) return 'N/A';

    const dateObj = new Date(date);

    if (isNaN(dateObj.getTime())) {
        return 'Fecha inválida';
    }

    // Delegar el formato dependiendo del tipo solicitado
    switch (formatType) {
        case 'long':
            return formatLongDate(dateObj);
        case 'medium':
            return formatMediumDate(dateObj);
        case 'short':
            return formatShortDate(dateObj);
        case 'withTime':
            return formatDateWithTime(dateObj);
        default:
            return formatDefaultDate(dateObj);
    }
};

// Formato largo: 24 de diciembre, 2025
const formatLongDate = (date: Date): string => {
    const options: Intl.DateTimeFormatOptions = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Intl.DateTimeFormat('es-ES', options).format(date);
};

// Formato mediano: 9 de marzo, 1995
const formatMediumDate = (date: Date): string => {
    const options: Intl.DateTimeFormatOptions = { day: 'numeric', month: 'long', year: 'numeric' };
    const dateParts = new Intl.DateTimeFormat('es-ES', options).formatToParts(date);

    // Accedemos a las partes de la fecha
    const day = dateParts.find((part) => part.type === 'day')?.value;
    const month = dateParts.find((part) => part.type === 'month')?.value;
    const year = dateParts.find((part) => part.type === 'year')?.value;

    // Retornamos en el formato "9 de marzo, 1995"
    return `${day} de ${month}, ${year}`;
};

// Formato corto: 24/12/25
const formatShortDate = (date: Date): string => {
    const options: Intl.DateTimeFormatOptions = { day: '2-digit', month: '2-digit', year: '2-digit' };
    return new Intl.DateTimeFormat('es-ES', options).format(date);
};

// Formato con hora: 24/12/25 8:30pm
const formatDateWithTime = (date: Date): string => {
    const options: Intl.DateTimeFormatOptions = {
        day: '2-digit',
        month: '2-digit',
        year: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    };
    const parts = new Intl.DateTimeFormat('es-ES', options).formatToParts(date);
    const day = parts.find((p) => p.type === 'day')?.value;
    const month = parts.find((p) => p.type === 'month')?.value;
    const year = parts.find((p) => p.type === 'year')?.value;
    const hour = parts.find((p) => p.type === 'hour')?.value;
    const minute = parts.find((p) => p.type === 'minute')?.value;
    const dayPeriod = parts.find((p) => p.type === 'dayPeriod')?.value?.toLowerCase();
    return `${day}/${month}/${year} ${hour}:${minute} ${dayPeriod}`;
};

// Formato predeterminado: 24/12/2025
const formatDefaultDate = (date: Date): string => {
    const options: Intl.DateTimeFormatOptions = { day: '2-digit', month: '2-digit', year: 'numeric' };
    return new Intl.DateTimeFormat('es-ES', options).format(date);
};
