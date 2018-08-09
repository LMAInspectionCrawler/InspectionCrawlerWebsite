using System.Web;
using System.Web.Mvc;

namespace Quality_Inspection_Crawlers
{
    public class FilterConfig
    {
        public static void RegisterGlobalFilters(GlobalFilterCollection filters)
        {
            filters.Add(new HandleErrorAttribute());
        }
    }
}
