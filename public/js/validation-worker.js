self.onmessage = function(e) {
    const data = e.data;
    const validationResults = [];
    
    function validateRUN(run) {
        if (!run || run.trim() === '') return false;
        
        run = run.replace(/[.-]/g, '').toUpperCase();
        if (run.length < 2) return false;
        
        let rut = run.slice(0, -1);
        let dv = run.slice(-1);
        
        let sum = 0;
        let multiplier = 2;
        
        for (let i = rut.length - 1; i >= 0; i--) {
            sum += Number(rut[i]) * multiplier;
            multiplier = multiplier === 7 ? 2 : multiplier + 1;
        }
        let expectedDv = 11 - (sum % 11);
        expectedDv = expectedDv === 11 ? '0' : expectedDv === 10 ? 'K' : expectedDv.toString();
        
        return dv === expectedDv;
    }

    function validateEmail(email) {
        if (!email || email.trim() === '') return false;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    data.forEach((row, index) => {
        const run = row[0];
        const email = row[2];
        const isValidRUN = validateRUN(run);
        const isValidEmail = validateEmail(email);
        
        validationResults.push({
            index,
            isValidRUN,
            isValidEmail,
            isRowValid: isValidRUN && isValidEmail
        });
    });

    self.postMessage(validationResults);
};