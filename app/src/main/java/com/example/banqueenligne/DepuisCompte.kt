package com.example.banqueenligne

import android.os.Bundle
import android.view.View
import android.view.Window
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.*
import com.android.volley.RequestQueue
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.flexbox.FlexboxLayout
import org.json.JSONArray
import org.json.JSONObject

class DepuisCompte : AppCompatActivity(){
    lateinit var mQueue : RequestQueue
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        setContentView(R.layout.depuis_compte)
        mQueue = Volley.newRequestQueue(this)
        listComptes();

        //actionbar
        val actionbar = supportActionBar
        //set actionbar title
        actionbar!!.title = "Depuis le compte"
        //set back button
        actionbar.setDisplayHomeAsUpEnabled(true)
    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressed()
        return true
    }

    fun listComptes() {
        val url = "http://192.168.0.18/onlineBankAPI/v1/?op=getAccounts" //IP A CHANGER
        lateinit var data : JSONObject
        lateinit var comptes : JSONArray

        val request = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener<String> { response ->
                data = JSONObject(response)
                comptes = data.optJSONArray("message")
                setComptes(comptes)
            },
            Response.ErrorListener { error: VolleyError ->
                println(error)
            }
        ){
            @Throws(AuthFailureError::class)
            override fun getParams() : Map<String, String> {
                val params = HashMap<String, String>()
                params["user_id"] = "2"
                return params
            }
        }

        mQueue.add(request)
    }

    fun setComptes(comptes:JSONArray) {

        var fetchedUser = intent.getStringExtra("user")
//        var user = JSONObject(fetchedUser)
//        var name :String = user["name"].toString()
//        var firstname :String = user["first_name"].toString()

            println(JSONArray(fetchedUser))
        for(i in 0 until comptes.length()){

            val parent = findViewById<FlexboxLayout>(R.id.displayAllBenef)
            val child : View = layoutInflater.inflate(R.layout.display_depuis_le_compte, parent, false)
            val box = child.findViewById<FlexboxLayout>(R.id.displayInfos)

            val compte : String = comptes!!.getJSONObject(i).optString("account_name")
            val amount : String = comptes!!.getJSONObject(i).optString("account_amount")

            child.findViewById<TextView>(R.id.nomCompte).text = compte
            child.findViewById<TextView>(R.id.montant).text = amount

            parent.addView(child)
        }
    }



    fun setUser(user:JSONArray) {
        for(i in 0 until user.length()){

            val parent = findViewById<FlexboxLayout>(R.id.displayAllBenef)
            val child : View = layoutInflater.inflate(R.layout.display_depuis_le_compte, parent, false)
            val box = child.findViewById<FlexboxLayout>(R.id.displayInfos)

            val compte : String = user!!.getJSONObject(i).optString("account_name")
            val amount : String = user!!.getJSONObject(i).optString("account_amount")

            child.findViewById<TextView>(R.id.nomCompte).text = compte
            child.findViewById<TextView>(R.id.montant).text = amount

            parent.addView(child)
        }
    }
}