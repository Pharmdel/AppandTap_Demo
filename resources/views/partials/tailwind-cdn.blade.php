<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    // Web (AppAndTap website) brand palette
                    redclr: '#FD4E3D',
                    blue: { DEFAULT: '#007AC2' },
                    darkBlue: '#0059ac',
                    lightBlue: '#002c57',
                    sky: { DEFAULT: '#1CA0FF' },
                    green: { DEFAULT: '#5FAD59' },
                    orange: { DEFAULT: '#FB6B1A' },
                    yellow: { DEFAULT: '#FEB906' },
                    purple: { DEFAULT: '#B56BA6' },
                    pink: { DEFAULT: '#FF7171' },
                    gray: { DEFAULT: '#475569' },
                    grey: '#DEDEDE',
                    // App (Flutter) brand palette
                    primaryColor: '#0069c8',
                    secondaryColor: '#004280',
                    bodyBgColor: '#F4F6F5',
                    greenColor: '#1C4332',
                    greenLightColor: '#EEF8F3',
                    skyColor: '#0676DD',
                    redColor: '#B05030',
                    redLighterColor: '#FFF8F4',
                    greyColor: '#A4A4A4',
                    greyLightColor: '#D8D8D8',
                    greyVeryLightColor: '#F1F1F1',
                    greyDarkColor: '#6F6F79',
                    nhsColor: '#005eb8',
                },
                fontFamily: {
                    Poppins: ['Poppins', 'sans-serif'],
                    DMSans: ['"DM Sans"', 'sans-serif'],
                    DMSerif: ['"DM Serif Display"', 'serif'],
                },
            },
        },
    };
</script>
<script src="https://unpkg.com/lucide@latest"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=DM+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
