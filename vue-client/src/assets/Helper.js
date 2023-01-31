
class Helper
{
    //static serverUrl = "http://127.0.0.1:5555";
	static serverUrl = "http://127.0.0.1:8000";

    static async FetchApi(route, method, body)
    {
        const requestOptions = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(body)
        }

        const response = await fetch(`${Helper.serverUrl}${route}`, requestOptions);
        const json = await response.json();
        return json;
    }

    static SetCookie(name, value, minutes) {
        let expires = "";
        if (minutes) {
            let date = new Date();
            date.setTime(date.getTime() + (minutes * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }

    static GetCookie(name) {
        let value = "; " + document.cookie;
        let parts = value.split("; " + name + "=");
        if (parts.length == 2) return parts.pop().split(";").shift();
    }


    static async Auth(state)
    {
        const token = Helper.GetCookie('token');
        const route = '/api/auth/';
        const method = 'POST';
        const body = {token: token};

        try
        {
            const response = await Helper.FetchApi(route, method, body);

            console.log(response)
            state.isLoggedIn = response.success;
            
        }
        catch(error)
        {
            console.log(error);
        }
    }

    static Logout(state)
    {
        state.isLoggedIn = false;
        Helper.SetCookie('token', '', 0);
    }
    
    static ToggleSpinner (on_off)
    {
        const element = document.getElementById('cover-spin');
        if (on_off) element.style.display = "block";
        else element.style.display = "none";
    }

    static checkDigit(event) {
        const code = (event.which) ? event.which : event.keyCode;
    
        if ((code < 48 || code > 57) && (code > 31)) {
            return false;
        }
    
        return true;
    }

    static cc_format(value) {
        let v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '')
        let matches = v.match(/\d{4,16}/g);
        let match = matches && matches[0] || ''
        let parts = []
    
        for (let i=0, len=match.length; i<len; i+=4) {
            parts.push(match.substring(i, i+4))
        }
    
        if (parts.length) {
            return parts.join(' ')
        } else {
            return value
        }
    }
    
}

export default Helper;