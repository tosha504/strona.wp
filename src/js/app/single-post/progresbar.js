export const progresbar = () => {
    document.addEventListener("scroll", function() {
        var scrollTop = document.documentElement["scrollTop"] || document.body["scrollTop"];
        var scrollBottom = (document.documentElement["scrollHeight"] || document.body["scrollHeight"]) - document.documentElement.clientHeight;  
        let scrollPercent = scrollTop / scrollBottom * 100 + "%";
        document.getElementById("progressBar") .style.setProperty("width", scrollPercent);
        },{ 
            passive: true 
        }
    );
}